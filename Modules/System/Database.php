<?php

/**
 * Database System Module
 *
 * @author Bálint Horváth <balint@snett.net>
 */

namespace Franklin\System;

class Database{

    private $config = array();
    private $link;
    private $current_db;
    
    public $LastQuery;
    public $DemoMode = false;

    public $DefaultEngine = "InnoDB";
    public $DefaultCharset = "utf8";
    
    private $KeptBack = array();

    public function __construct($config) {
        if (is_array($config) && count($config) > 0){
            $this->Auto($config);
        }
    }
    
    public function Auto($config){
        $this->setConfig($config);
        $this->Connect();
        $this->selectDb();
        $this->setCharset();
    }
    
    public function setConfig($config){
        $this->config = $config;
        if (empty($this->config['port'])){
            $this->config['port'] = null;
        }
        if (empty($this->config['socket'])){
            $this->config['socket'] = null;
        }
    }
    
    public function Connect(){
        $this->link = mysqli_connect($this->config['server'], $this->config['user'], $this->config['password'], $this->config['port'], $this->config['socket']);
    }
    
    public function Choose($db){
        $this->select_db($db);
    }
    
    public function selectDb($db=false){
        if (!$db){
            mysqli_select_db($this->link, $this->config['database']);
            $this->current_db = $this->config['database'];
        } else{
            mysqli_select_db($this->link, $db);
            $this->current_db = $db;
        }
    }
    
    public function KeepBack($Query){
        $this->KeptBack[] = $Query;
    }
    
    public function getKeptBack($Serialize=false){
        if ($Serialize){
             return(implode("", $this->KeptBack));
        } else{
            return($this->KeptBack);
        }
    }
    
    public function runKeptBack() {
        $Report = array();
        foreach ($this->KeptBack as $KeptBack) {
            $Report[] = $this->Query($KeptBack);
        }
        return($Report);
    }

    public function getLastError(){
        return(mysqli_error($this->link));
    }

    public function getLastErrno(){
        return(mysqli_errno($this->link));
    }
    
    public function getLastQuery() {
        return($this->LastQuery);
    }

    public function getCurrentDatabase(){
        return($this->current_db);
    }
    
    public function setCharset($charset='utf8'){
        mysqli_set_charset($this->link,$charset);
        mysqli_query($this->link,"SET NAMES '".$charset."';");
    }

    public function Query($query){
        $this->LastQuery = $query;
        if ($this->DemoMode === true){
            return(false);
        } else{
            return(mysqli_query($this->link, $query));
        }
    }
    
    public static function FetchArray($result){
        if (mysqli_num_rows($result) > 0){
            return(mysqli_fetch_array($result, MYSQLI_ASSOC));
        } else{
            return(false);
        }
    }
    
    public static function FetchRow($result){
        if ($result !== false){
            if (mysqli_num_rows($result) > 0){
                return(mysqli_fetch_row($result));
            } else{
                return(false);
            }
        } else{
            return(false);
        }
    }
    
    public static function NumRows($result){
        return(mysqli_num_rows($result));
    }
    
    public function LastInsertId() {
        return(mysqli_insert_id($this->link));
    }
    
    public function Escape($unescaped) {
        $ReplaceFrom[] = '\\';
        $ReplaceTo[] = '\\\\';
        $ReplaceFrom[] = "'";
        $ReplaceTo[] = '\\\'';
        return(str_replace($ReplaceFrom, $ReplaceTo, $unescaped));
        //return(mysqli_real_escape_string($unescaped));
    }
    
    public function Exists($table, $conditions) {
        $query = "SELECT COUNT(*) FROM `$table` WHERE ";
        $ci = 0;
        foreach ($conditions as $condition_field => $condition_value) {
            if ($ci == count($conditions) -1 ){
                $query .= "`$condition_field`='$condition_value'";
            } else{
                $query .= "`$condition_field`='$condition_value' AND";
            }
            $ci++;
        }
        $query .= ";";
        $result = $this->FetchRow($this->Query($query));
        if($result[0] > 0){
            return(true);
        } else{
            return(false);
        }
    }
    
    public function getRow($table, $conditions) {
        $query = "SELECT * FROM `$table`";
        if (is_array($conditions) && count($conditions) > 0){
            $query .= " WHERE";
            $ci = 0;
            foreach ($conditions as $condition_field => $condition_value) {
                if ($ci == count($conditions) -1 ){
                    $query .= " `$condition_field`='$condition_value'";
                } else{
                    $query .= " `$condition_field`='$condition_value' AND";
                }
                $ci++;
            }
        }
        $query .= ";";
        $result = $this->FetchArray($this->Query($query));
        return($result);
    }
    
    public function removeRow($table, $conditions) {
        $query = "DELETE FROM `$table`";
        if (is_array($conditions) && count($conditions) > 0){
            $query .= " WHERE";
            $ci = 0;
            foreach ($conditions as $condition_field => $condition_value) {
                if ($ci == count($conditions) -1 ){
                    $query .= " `$condition_field`='$condition_value'";
                } else{
                    $query .= " `$condition_field`='$condition_value' AND";
                }
                $ci++;
            }
        }
        $query .= ";";
        $result = $this->Query($query);
        return($result);
    }
    
    public function getData($table, $filter="", $order="", $limit=100, $joins="", $groupby="", $rows="*") {
        $data = false;
        if (empty($filter)){
            $where = "";
        } else{
            $where = " WHERE $filter";
        }
        $postsyntax = "";
        if (!empty($joins)){
            $postsyntax .= $joins;
        }
        if (!empty($groupby)){
            $postsyntax .= " GROUP BY " . $groupby;
        }
        if (!empty($where)){
            $postsyntax .= " " . $where;
        }
        $orderby = "";
        if (!empty($order)){
            $orderby = " ORDER BY $order";
        }
        $query = "SELECT $rows FROM `$table`$postsyntax $orderby LIMIT $limit;";
        $result = $this->Query($query);
        if ($result !== false){
            while ($row = $this->FetchArray($result)){
                $data[] = $row;
            }
            return($data);
        } else{
            return(false);
        }
    }
    
    public function getExactData($field=0, $table="", $filter=array(), $order="", $limit=10) {
        if (empty($filter)){
            $where = "";
        } else{
            $where = " WHERE $filter";
        }
        if (!empty($order)){
            $orderby = " ORDER BY $order";
        }
        $query = "SELECT * FROM `$table`$where $orderby LIMIT $limit;";
        $result = $this->FetchRow($this->Query($query));
        return($result[$field]);
    }
    
    public function InsertArray($table, $array) {
        $query = "INSERT INTO `$table` (`";
        $query .= implode("`,`",array_keys($array));
        $query .= "`) VALUES(";
        $vi = 0;
        foreach ($array as $value) {
            $value = $this->Escape($value);
            if ($vi == count($array)-1){
                $query .= "'$value'";
            } else{
                $query .= "'$value',";
            }
            $vi++;
        }
        $query .= ");";
        return($this->Query($query));
    }
    
    public function UpdateArray($table, $array, $conditions) {
        $query = "UPDATE `$table` SET ";
        $vi = 0;
        foreach ($array as $field => $value) {
            $value = $this->Escape($value);
            if ($vi == count($array)-1){
                $query .= "`$field`='$value'";
            } else{
                $query .= "`$field`='$value',";
            }
            $vi++;
        }
        if (is_array($conditions) && count($conditions) > 0){
            $query .= " WHERE";
            $ci = 0;
            foreach ($conditions as $condition_field => $condition_value) {
                if ($ci == count($conditions) -1 ){
                    $query .= " `$condition_field`='$condition_value'";
                } else{
                    $query .= " `$condition_field`='$condition_value' AND";
                }
                $ci++;
            }
        }
        $query .= ";";
        return($this->Query($query));
    }
    
    public function TableExists($TableName) {
        if ($this->NumRows($this->Query("SHOW TABLES LIKE '$TableName';")) > 0){
            return(true);
        } else{
            return(false);
        }
    }
    
    public function CreateTable($TableName, $Fields, $Options=array()) {
        if ($this->TableExists($TableName)){
            throw new Exception("Table $TableName already exists so unable to create. Maybe you want to change it using EditTable method.");
        }
        /*Basics*/
        $FieldCount = (int) 0;
        $Last = (bool) false;
        $First = (bool) false;
        $PrimaryKey = (string) "";
        $PrimaryKeyOpinions = array();
            $PrimaryKeyOpinions[0] = false;
        $Indexes = array();
            $Indexes['index'] = array();
            $Indexes['unique'] = array();
            $Indexes['fulltext'] = array();
        $CharsetAliases = array();
        $Settings = array();
        $DefaultValues = array();
        $FirstField = array();
        $ObjectFields = array();
        $AfterQ = "";
        $AfterQCount = 0;
        /*Set defaults*/
        $TableEngine = $this->DefaultEngine;    //InnoDB
        $TableCharset = $this->DefaultCharset;
        $Settings['auto_increment'] = false;
        $DefaultValues['auto_increment'] = 1;
        /*Define charset aliases*/
        $CharsetAliases['UTF-8'] = "utf8";
        /*Enable lowercase keys*/
        foreach ($Options as $OptionKey => $Option) {
            $Options[strtolower($OptionKey)] = $Option;
        }
        /*Start Query*/
        $Q = "CREATE TABLE IF NOT EXISTS `$TableName` (";
        foreach ($Fields as $Field) {
            if ($FieldCount == count($Fields)-1){
                $Last = true;
            }
            if ($FieldCount == 0){
                $First = true;
            }
            /*Enable lowercase keys*/
            foreach ($Field as $FieldKey => $FieldValue) {
                $Field[strtolower($FieldKey)] = $FieldValue;
            }
            /*Store first field name*/
            if ($First){
                $FirstField = $Field;
            }
            /*Set field name*/
            $Q .= "`".$Field['name']."`";
            $Q .= " ";
            /*Set field type*/
            switch (strtolower($Field['type'])) {
                //int()
                case 'num':
                case 'number':
                case 'int':
                case 'integer':
                    $Q .= "int(".$Field['length'].")";
                break;
                //varchar()
                case 'string':
                case 'var':
                case 'varchar':
                    $Q .= "varchar(".$Field['length'].")";
                break;
                //timestamp
                case 'timestamp':
                case 'datetime':
                case 'time':
                    $Q .= "timestamp";
                break;
                //boolean
                case 'bool':
                case 'boolean':
                    $Q .= "tinyint(1)";
                break;
                //text
                case 'text':
                    $Q .= "text";
                break;
                //blob
                case 'blob':
                    $Q .= "blob";
                break;
                //longblob
                case 'longblob':
                    $Q .= "longblob";
                break;
                case 'object':
                    $Q .= "int(8)";
                    $ObjectFields[] = $Field;
                break;
                default:
                break;
            }
            /*Set NULL or NOT NULL*/
            if ($Field['notnull'] != 'true'){
                $Q .= " NULL";
            } else{
                $Q .= " NOT NULL";
            }
            /*Set extras: Auto increment*/
            if ((!empty($Field['extras']) && in_array('autoincrement', $Field['extras'])) || strtolower($Field['default']) == "@next"){
                if ($Settings['auto_increment'] !== true){
                    $Q .= " AUTO_INCREMENT";
                    $Settings['auto_increment'] = true;
                } else{
                    //throw new Exception("Multiple AutoIncrement fields defined and it's not allowed.");
                    return(false);
                }
            }
            /*Set default value*/
            if (!empty($Field['default'])){
                switch (strtolower($Field['default'])) {
                    case 'now':
                    case 'current':
                    case 'current_timestamp':
                        $Q .= " DEFAULT CURRENT_TIMESTAMP";
                    break;
                    case '$next':
                    break;
                    default:
                        $DefaultLC = strtolower($Field['default']);
                        if ($DefaultLC[0] !== "$"){
                            $Q .= " DEFAULT `".$Field['default']."`";
                        }
                    break;
                }
            }
            /*Store Indexes*/
            if (!empty($Field['index'])){
                /*Allow multiple indexes*/
                $FieldIndexes = explode(',', $Field['index']);
                foreach ($FieldIndexes as $FieldIndex) {
                    switch ($FieldIndex) {
                        case 'primary':
                        case 'primarykey':
                            $PrimaryKey = $Field['name'];
                        break;
                        case 'unique':
                            $Indexes['unique'][] = $Field['name'];
                        break;
                        case 'text':
                        case 'fulltext':
                            $Indexes['fulltext'][] = $Field['name'];
                        break;
                        default:
                            $Indexes['index'][] = $Field['name'];
                        break;
                    }
                }
            }
            /*Guess primary key*/
            if (strtolower($Field['name']) == "id"){
                $PrimaryKeyOpinions[0] = $Field['name'];
            } else if (stripos("id", $Field['name'])){
                $PrimaryKeyOpinions[] = $Field['name'];
            }
            /*Put separator*/
            if (!$Last){
                $Q .= ",";
            }
            /*++*/
            $FieldCount++;
        }
        if ($Options['dropprimary'] !== true){
            if (empty($PrimaryKey)){
                if ($PrimaryKeyOpinions[0] !== false){
                    $PrimaryKey = $PrimaryKeyOpinions[0];
                } else if (!empty($PrimaryKeyOpinions[1])){
                    $PrimaryKey = $PrimaryKeyOpinions[1];
                } else{
                    $PrimaryKey = $FirstField['name'];
                }
            }
            $Q .= ", PRIMARY KEY (`$PrimaryKey`)";
        }
        $IndexCount = array();
        if ($Options['dropindexes'] !== true){
            if ($Options['dropindexes:index'] !== true){
                if (count($Indexes['index']) > 0){
                    foreach ($Indexes as $Index) {
                        if ($IndexCount[$Index] > 0){
                            $Index .= "_" . $IndexCount[$Index];
                        }
                        $Q .= ", KEY `$Index` (`$Index`)";
                        $IndexCount[$Index]++;
                    }
                }
                if (count($ObjectFields) > 0){
                    foreach ($ObjectFields as $ObjectField) {
                        $ObjectFieldName = $ObjectField['name'];
                        $Q .= ", INDEX `$ObjectFieldName` (`$ObjectFieldName`)";
                        $IndexCount[$Index]++;
                    }
                }
            }
            if ($Options['dropindexes:unique'] !== true){
                if (count($Indexes['unique']) > 0){
                    foreach ($Indexes as $Index) {
                        if ($IndexCount[$Index] > 0){
                            $Index .= "_" . $IndexCount[$Index];
                        }
                        $Q .= ", UNIQUE KEY `$Index` (`$Index`)";
                        $IndexCount[$Index]++;
                    }
                }
            }
            if ($Options['dropindexes:fulltext'] !== true){
                if (count($Indexes['fulltext']) > 0){
                    foreach ($Indexes as $Index) {
                        if ($IndexCount[$Index] > 0){
                            $Index .= "_" . $IndexCount[$Index];
                        }
                        $Q .= ", FULLTEXT KEY `$Index` (`$Index`)";
                        $IndexCount[$Index]++;
                    }
                }
            }
            if ($Options['dropforeignkeys'] !== true){
                if (count($ObjectFields) > 0){
                    foreach ($ObjectFields as $ObjectField) {
                        if (empty($ObjectField['object'])){
                            $ObjectField['object'] = $ObjectField['name'];
                        }
                        if ($ObjectField['relation'] == "many"){
                            $RelationTableName = $TableName . "_" . $ObjectField['object'];
                            //$RelationTableReversedName = $TableName . "_" . $ObjectField['object'];
                            if (!$this->TableExists($RelationTableName)){
                                $RelationFields = array(
                                    array(
                                        "Name" => "Id",
                                        "Type" => "int",
                                        "Length" => 8,
                                        "Index" => "primary",
                                        "Extras" => array('autoincrement')
                                    ),
                                    array(
                                        "Name" => $TableName,
                                        "Type" => "object",
                                        "Relation" => "one"
                                    ),
                                    array(
                                        "Name" => $ObjectField['object'],
                                        "Type" => "object",
                                        "Relation" => "one"
                                    )
                                );
                                $this->CreateTable($RelationTableName, $RelationFields);
                            }
                        } else {
                            $AfterQ = "ALTER TABLE `$TableName` ADD ";
                            //$AfterQ .= "CONSTRAINT `FK_".$ObjectField['name']."` FOREIGN KEY (`";
                            $AfterQ .= "CONSTRAINT `fk_".$TableName."_".$ObjectField['object']."` FOREIGN KEY (`";
                            $AfterQ .= $ObjectField['name'];
                            $AfterQ .= "`)";
                            $AfterQ .= " REFERENCES ";
                            $AfterQ .= "`" . $ObjectField['object'] . "` (`id`)";
                            //$AfterQ .= " ON UPDATE CASCADE ON DELETE CASCADE";
                            $this->KeepBack($AfterQ);
                            $AfterQCount++;
                        }
                    }
                }
            }
        }
        $Q .= ")";
        /*Allow custom database engine*/
        if (!empty($Options['engine'])){
            $TableEngine = $Options['engine'];
        }
        /*Allow custom charset*/
        if (!empty($Options['charset'])){
            $TableCharset = $Options['charset'];
        }
        if (!empty($Options['collation'])){
            $TableCharset = $Options['collation'];
        }
        if (array_key_exists($TableCharset, $CharsetAliases)){
            $TableCharset = $CharsetAliases[$TableCharset];
        }
        /*Set table options*/
        $Q .= " ENGINE=$TableEngine DEFAULT CHARSET=$TableCharset";
        /*Set AUTO_INCREMENT if needed*/
        if ($Settings['auto_increment'] === true){
            $Q .= " AUTO_INCREMENT=".$DefaultValues['auto_increment']."";
        }
        $Q .= ";";
        
        /*Return the results*/
        return($this->Query($Q));
    }
    
    public function RemoveTable($TableName) {
        $Q = "DROP TABLE `$TableName`;";
        return($this->Query($Q));
    }
    
    public function EditTable($Fields, $Options=array()) {
        //TODO implement EditTable
        throw new Exception("EditTable is not implemented yet.");
        //return(false);
    }
    
}

