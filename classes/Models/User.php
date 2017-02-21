<?php

/**
 * Created by PhpStorm.
 * User: itunu.babalola
 * Date: 1/18/17
 * Time: 8:19 AM
 */
namespace Model;
use \Illuminate\Database\Eloquent\Model as EModel;
use Model\Contact;

class User extends  EModel
{
    protected  $table = 'users';
    protected  $fillable = ['user_name','phone_number','address','password'];
    protected $guarded =['id'];


    public static function contacts(){
        $user = new User();
        return $user->hasMany('Model\Contact');
    }

    public  static function  allUsers()

    {
        $users = User::all();
        return $users;
    }


    public static function createUser($user)
    {
       $newUser = new User();
       $newUser->user_name = $user['user_name'];
       $newUser->phone_number = $user['phone_number'];
       $newUser->address = $user['address'];
       $newUser->password = password_hash($user['password'],PASSWORD_BCRYPT);
       $newUser->save();
       $id = $newUser->id;
       $newUser->id = $id;
       return $newUser;
    }


    public static function  login($user){

        $user = User::where('phone_number',$user['phone_number'])->get();/*->where('password',password_verify($user['password'],'PASSWORD_BYCRYPT')*/
        if($user[0] != null){
            return $user;
        }
        else{
            return false;
        }

    }


    public static  function allContacts($user_id){
        $contacts = Contact::getUserContacts($user_id);
        if($contacts.items[0] != null){
            return $contacts;
        }
        else{
            return false;
        }

    }





}
