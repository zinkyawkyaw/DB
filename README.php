<?PHP 
# phpdatabaseclass
//php mysqli database class
//first of all
DB::connection();

$posts = DB::secure($_POST) ;//it will escape unsecure character.
$name = $posts['name'];

//set own connection
DB::connection('host_name','user_name','password','database_name'); 
DB::table("table_name");  //every code must set table name first

//to get all data
DB::all();


//find by id
DB::find("id_number");   //only number


//find by condition //
DB::findCondition("WHERE your condition");



//save data
$data = [
  "column_name1" => $your_data,  //form your mysql column name
  "column_name2" => $your_data2
];
DB::save($data);


//update data
$data = [
  "column_name1" => $your_data,  //form your mysql column name
  "column_name2" => $your_data2
];
DB::update("id_number" , $data);//only number


//update where
$data = [
  "column_name1" => $your_data,  //form your mysql column name
  "column_name2" => $your_data2
];
DB::updateWhere("WHERE condition" , $data);


//destroy data
DB::destroy("id_number");


//destroy where
DB::destroyWhere("WHERE condition");


//close connection
DB::closeConnection();

//own query
DB::query("YOUR Query");


//extra
$result = DB::select()->where()->limit(2)->orderby('title desc')->get();
//get last id of inset data
DB::$last_id;



//extends class
Class Posts extends DB{
   //no need to set table name;
   //it will search form table using parent class name(Posts);
}
