<?php

/**
 * Created by PhpStorm.
 * User: itunu.babalola
 * Date: 1/18/17
 * Time: 8:19 AM
 */
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Interop\Container\ContainerInterface;
use Model\User;
use Utils\General_Utils;
use Model\Contact;

class UserController
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }


    public function getUsers(Request $request, Response $response,$args){
        $all_users = User::allUsers();
        $data = [];
       if($all_users) {
           $data = $all_users->toArray();
       }
        $return_data = ['status'=>'success','data'=>$data];
        return $response->withJson($return_data);

    }


    public function registerUser(Request $request, Response $response,$args){
        try{
            $user_details = $request->getParsedBody();
            $data =[];
            if(($user_details['user_name']!== null)  && ($user_details['phone_number'] !== null) && ($user_details['address'] !== null) && ($user_details['password'] !== null)){
                $new_user=  User::createUser($user_details);
                $data = $new_user->toArray();
                $return_data =['status'=>'success','data'=>$data];
                return  $response->withJson($return_data);
            }
            else{
                $data = ['status'=>'error','code'=>'E101','message'=>'Sorry you have to fill in your name, phonenumber,password and address when registering'];
                return  $response->withJson($data);
            }
        }
            catch(Exception $e){
            $data =['status'=>'error_database','message'=>$e->getMessage()];
                return  $response->withJson($data);
            }

    }

    public  function loginUser(Request $request,Response $response,$args){
       try{
           $loginData = $request->getParsedBody();

           if($loginData['phone_number'] != null && $loginData['password'] != null){
               $checkUser = User::login($loginData);
               if($checkUser != false){
                   $data =[];
                   $data = $checkUser->toArray();
                   $data['session_id'] = General_Utils::getInstance()->startSession('id','loggedin'.$data[0]['phone_number']);
                   $return_data =['status'=>'success','data'=>$data];
                   return  $response->withJson($return_data);

               }
               else{
                   $data = ['status'=>'error','code'=>'E102','message'=>'Wrong Phone_number,Password,Please check again'];
                   return  $response->withJson($data);
               }
           }
       }catch (Exception $e){
           $data =['status'=>'error','message'=>$e->getMessage()];
           return  $response->withJson($data);
       }

    }


    public function getContacts(Request $request,Response $response,$arg){
        try{
            $user_id = $arg['user_id'];
            $data = [];
            $contacts__ =[];
            if($user_id != null){
                $contacts = User::allContacts($user_id);
                if($contacts != false){
                    $data = $contacts->toArray();
                    $contacts__ = ['contacts' => $data];
                    $return_data =['status'=>'success','data'=>$contacts__];
                    return  $response->withJson($return_data);
                }
                else{
                    $return_data =['status'=>'Notice','code'=>'N101','message'=>'You dont have any contacts yet'];
                    return  $response->withJson($return_data);
                }
            }
            else{
                $return_data =['status'=>'error','code'=>'E103','message'=>'You have to provide a valid user_id'];
                return  $response->withJson($return_data);
            }
        }catch (Exception $e){
            $data =['status'=>'error','message'=>$e->getMessage()];
            return  $response->withJson($data);
        }

    }


    public function addNewContact(Request $request,Response $response,$args){
        try{
            $user_id = $args['user_id'];
            $contact = $request->getParsedBody();
            $data =[];
            if($user_id != null){
                if($contact['contact_name'] !=null && $contact['phone_number'] != null && $contact['address'] != null){
                    $newContact = Contact::createContact($contact,$user_id);
                    $data = $newContact->toArray();
                    $return_data =['status'=>'success','data'=>$data];
                    return  $response->withJson($return_data);

                }
                else{
                    $data = ['status'=>'error','code'=>'E103','message'=>'Sorry you have to fill in the contacts details when registering'];
                    return  $response->withJson($data);
                }
            }else{
                $data = ['status'=>'error','code'=>'E103','message'=>'No user_id specified'];
                return  $response->withJson($data);

            }




        }catch (Exception $e){
            $data =['status'=>'error','message'=>$e->getMessage()];
            return  $response->withJson($data);

        }

    }


    public  function updateAContact(Request $request,Response $response,$args){
        try{
            $contact_id = $args['contact_id'];
            if($contact_id != null){
                $contactUpdate = $request->getParsedBody();
                $updatedContact = Contact::updateContact($contact_id,$contactUpdate);
                if($updatedContact != false){
                    $data = $updatedContact->toArray();
                    $return_data =['status'=>'success','data'=>$data];
                    return  $response->withJson($return_data);

                }elseif ($updatedContact == false){
                    $data = ['status'=>'Notice','code'=>'N104','message'=>'No contact found with that ID or No update fields specified'];
                    return  $response->withJson($data);
                }
            }
            else{
                $data = ['status'=>'error','code'=>'E104','message'=>'No Contact Id specified'];
                return  $response->withJson($data);
            }

        }catch (Exception $e){
            $data =['status'=>'error','message'=>$e->getMessage()];
            return  $response->withJson($data);
        }


    }


    public  function deleteAContact(Request $request, Response $response,$args){
        try {
            $contact_id = $args['contact_id'];
            if ($contact_id != null) {
                $delete_contact = Contact::deleteContact($contact_id);
                if($delete_contact == true){
                    $return_data =['status'=>'success','data'=>'Contact deleted successfully'];
                    return  $response->withJson($return_data);
                }
                else{
                    $return_data =['status'=>'error','data'=>'Contact could not be deleted or contact doesnt exist'];
                    return  $response->withJson($return_data);
                }



            } else {
                $data = ['status' => 'error', 'code' => 'E104', 'message' => 'No Contact Id specified'];
                return $response->withJson($data);
            }

        }catch (Exception $e){
            $data =['status'=>'error','message'=>$e->getMessage()];
            return  $response->withJson($data);
        }

    }

}