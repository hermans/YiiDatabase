ReadMe

I got some problem when work with Yii + PDO:
- Not all hosting support with PDO
- mostly hosting support for mssql_connect and mysql_connect

Main Configuration (main.php)
for MySQL:
[code]
'db'=>array(
	'config'=>array(
		'host'=>'localhost',
		'username'=>'root',
		'password'=>'123',
		'dbname'=>'myDbTest',
		'charset' => 'utf8',
		),
	'class'=>'application.components.db.dbMysql',
),
[/code]

for SQL Server:
[code]
'db'=>array(
	'config'=>array(
		'host'=> 'local\SQLEXPRESS',
		'username'=>'sa',
		'password'=>'123',
		'dbname'=>'myDbTest',
		'charset' => 'utf8',
		),
	'class'=>'application.components.db.dbMssql',
),
[/code]

How to use:
[code]
class MyDB
{
	function getCustomerById($id)
	{
		$sql = "select * From customer where customer_id='".$id."'";
		$rs = Yii::app()->db->query($sql);
		return $rs->row;
	}
}
[/code]
