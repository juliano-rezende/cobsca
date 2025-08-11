<?php
class login_attempts extends ActiveRecord\Model{

	static $table_name='tentativas_login';


	public static function TotalDeTentativas($user_id)
	{
		return count(self::find_all_by_usuario_id($user_id));
	}

	public static function TentativasRestantes($user_id)
	{
		return intval(4-self::TotalDeTentativas($user_id));
	}

	public static function RegistrarTentativa($user_id)
	{
		self::create(array(
			'usuario_id' => $user_id
		));
	}

	public static function LimparTentativas($user_id)
	{
		self::delete_all(array('conditions' => array('usuario_id = ?',$user_id)));
	}

	public static function ExistemTentativas($user_id)
	{
		return self::TotalDeTentativas($user_id) < 5 ? true : false;
	}















}
?>