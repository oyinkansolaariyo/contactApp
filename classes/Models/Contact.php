<?php

/**
 * Created by PhpStorm.
 * User: itunu.babalola
 * Date: 1/18/17
 * Time: 8:48 AM
 */
namespace Model;

use \Illuminate\Database\Eloquent\Model as EModel;


class Contact extends EModel
{
    protected  $table = 'contacts';
    protected  $fillable = ['contact_name','phone_number','address','user_id'];
    protected $guarded =['id'];

    public function contact(){
        return $this->belongsTo('Model\User');
    }


   public static function getUserContacts($user_id){
        $contacts = Contact::all()->where('user_id',$user_id);
        return $contacts;
   }

   public  static function  createContact($contact,$user_id){
      $new_contact =  new Contact();
      $new_contact->contact_name = $contact['contact_name'];
      $new_contact->phone_number = $contact['phone_number'];
      $new_contact->address = $contact['address'];
      $new_contact->user_id = $user_id;
      $new_contact->save();
      $id =$new_contact->id;
      $new_contact->id =$id;
      return $new_contact;
   }

   public static function updateContact($contact_id,$contactUpdates){
       $contact = Contact::find($contact_id);

       if($contact != null){
           if(isset($contactUpdates['contact_name'])){
               $contact->contact_name = $contactUpdates['contact_name'];
           }
           if (isset($contactUpdates['phone_number'])){
               $contact->phone_number = $contactUpdates['phone_number'];
           }
           if (isset($contactUpdates['address'])){
               $contact->address = $contactUpdates['address'];
           }
           if($contactUpdates == null){
               return false;
           }
           $contact->save();
           return $contact;
       }else{
           return false;
       }


   }

   public  static function deleteContact($contact_id){
      $deleted = Contact::destroy($contact_id);
      if($deleted){
          return true;
      }
      else{
          return false;
      }

   }




}