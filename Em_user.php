<?php namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use Exception;
use \Firebase\JWT\JWT;

class Em_user extends ResourceController
{
    protected $modelName = 'App\Models\Em_usermodel';
    protected $format = 'json';

    public function index()
    {
        return $this->respond($this->model->findAll(),200);
    }

    //token firebase
    public function tokenfb($id = null){
        if($id==null)
        {
            $code = 404;
            $msg = ['message' => 'Id cannot be null'];
            $response = [
                    'status' => $code,
                    'error' => false,
                    'data' => $msg,
                ];
        }else
        {
            $find = $this->model->getWhere(['id_user' => $id])->getResult();
            if($find)
            {
                $data = $this->request->getRawInput();
                $simpan = $this->model->updateUser($data,$id);
                    
                    if($simpan){
                        $code = 200;
                        $msg = ['message' => 'Updated Data User Successfully'];
                        $response = [
                            'status' => $code,
                            'error' => false,
                            'data' => $msg,
                        ];

                    } 

            }else{
                $code = 404;
                $msg = ['message' => 'No data Found with Id : ' .$id];
                $response = [
                    'status' => $code,
                    'error' => false,
                    'data' => $msg,];
            }
            

        }
        return $this->respond($response, $code);
        
    }
    // Read Data
    public function show($id = null)
    {
       
        if($id==null)
        {
            $dt= $this->model->findAll();
            $code = 200;
            $response = [
                'status' => $code,
                'error' => false,
                'data' => $dt,
            ];
        }else
        {
            $get = $this->model->getUser($id);
            if($get){
                $code = 200;
                $response = [
                    'status' => $code,
                    'error' => false,
                    'data' => $get,
                ];
            } else {
                $code = 401;
                $msg = ['message' => 'Data Not Found with id' .$id];
                $response = [
                    'status' => $code,
                    'error' => true,
                    'data' => $msg,
                ];
            }
        }

        
        
        return $this->respond($response, $code);
    }

    // Create data
    public function create()
    {
        $validation =  \Config\Services::validation();

        $data = [
            'id_user' => $this->request->getPost('id_user'),
            'id_jabatan' => $this->request->getPost('id_jabatan'),
            'nama' => $this->request->getPost('nama'),
            'usernm' => $this->request->getPost('usernm'),
            'passwd' => $this->request->getPost('passwd'),
            'telp'=> $this->request->getPost('telp'),
            'status' => $this->request->getPost('status'),
            'id_level' => $this->request->getPost('id_level'),
            'nip' => $this->request->getPost('nip'),
            'alamat' => $this->request->getPost('alamat'),
            'tempat' => $this->request->getPost('tempat'),
            'tgl_lahir' => $this->request->getPost('tgl_lahir'),
            // 'foto' => $this->request->getPost('foto'),
            'entry'=> $this->request->getPost('entry'),
            'esign_active' => $this->request->getPost('esign_active'),
            'admin_nodin' => $this->request->getPost('admin_nodin'),
            'token'=> $this->request->getPost('token'),

            //foto getfile
            'foto' => $this->request->getFile('foto'),

            
        ];

        if($validation->run($data, 'em_userval') == FALSE){
            $code = 500;
            $response = [
                'status' => $code,
                'error' => true,
                'data' => $validation->getErrors(),
            ];
        }else{
            $save = $this->model->insertUser($data);
            if($save)
            {
                $code = 200;
                $response = [
                    'status' => $code,
                    'error' => false,
                    'data' => $data,
                ];         
            }
        }
		return $this->respond($response,$code);
    }

    // edit
    public function edit($id = NULL)
    {
        $get = $this->model->getUser($id);
        if($get){
            $code = 200;
            $response = [
                'status' => $code,
                'error' => false,
                'data' => $get,
            ];
        } else {
            $code = 401;
            $msg = ['message' => 'Data Not Found with id'.$id];
            $response = [
                'status' => $code,
                'error' => true,
                'data' => $msg,
            ];
        }
        return $this->respond($response, $code);
    }

    // Update Data
    public function update($id = null)
    {     
        if($id==null)
        {
            $code = 404;
            $msg = ['message' => 'Id cannot be null'];
            $response = [
                    'status' => $code,
                    'error' => false,
                    'data' => $msg,
                ];
        }else
        {
            $find = $this->model->getWhere(['id_user' => $id])->getResult();
            if($find)
            {
                $data = $this->request->getRawInput();
                $simpan = $this->model->updateUser($data,$id);
                    
                    if($simpan){
                        $code = 200;
                        $msg = ['message' => 'Updated Data User Successfully'];
                        $response = [
                            'status' => $code,
                            'error' => false,
                            'data' => $msg,
                        ];

                    } 

            }else{
                $code = 404;
                $msg = ['message' => 'No data Found with Id : ' .$id];
                $response = [
                    'status' => $code,
                    'error' => false,
                    'data' => $msg,];
            }
            

        }
        return $this->respond($response, $code);
        
    }

    // Delete Data
    public function delete($id = null)
    {
        if($id == null)
        {
            $code = 404;
            $msg = ['message' => 'Id cannot be null'];
            $response = [
                    'status' => $code,
                    'error' => false,
                    'data' => $msg,
                ];
        }else
        {
            $data = $this->model->getWhere(['id_user' => $id])->getResult();
            if($data){
                $del = $this->model->deleteUser($id);
                if($del)
                {
                    $code = 201;
                    $response = [
                        'status'   => $code,
                        'error'    => null,
                        'messages' => [
                            'success' => 'Data Deleted'
                        ]
                    ];
                }else
                {
                    $code = 201;
                    $response = [
                        'status'   => $code,
                        'error'    => null,
                        'messages' => [
                            'success' => 'Data not Deleted'
                        ]
                    ];
                }
            }else{
                $code = 404;
                $msg = ['message' => 'No data Found with Id' .$id];
                $response = [
                    'status' => $code,
                    'error' => false,
                    'data' => $msg,];
            }

        }
        return $this->respond($response,$code);
    }        

    public function register()
    {
        $validation =  \Config\Services::validation();

        // $userModel = new UserModel();
        $data = [
            'id_user' => $this->request->getPost('id_user'),
            'id_jabatan' => $this->request->getPost('id_jabatan'),
            'nama' => $this->request->getPost('nama'),
            'usernm' => $this->request->getPost('usernm'),
            // 'passwd' => $this->request->getPost('passwd'),
            "passwd" => password_hash($this->request->getVar("passwd"), PASSWORD_DEFAULT),
            'telp'=> $this->request->getPost('telp'),
            'status' => $this->request->getPost('status'),
            'id_level' => $this->request->getPost('id_level'),
            'nip' => $this->request->getPost('nip'),
            'alamat' => $this->request->getPost('alamat'),
            'tempat' => $this->request->getPost('tempat'),
            'tgl_lahir' => $this->request->getPost('tgl_lahir'),
            'foto' => $this->request->getPost('foto'),
            'entry'=> $this->request->getPost('entry'),
            // 'esign_active' => $this->request->getPost('esign_active'),
            // 'admin_nodin' => $this->request->getPost('admin_nodin'),
            'token'=> $this->request->getPost('token'),

            //foto getfile
            // 'foto' => $this->request->getFile('foto'),

            
        ];

        // $data = [
        //     "name" => $this->request->getVar("name"),
        //     "email" => $this->request->getVar("email"),
        //     "phone_no" => $this->request->getVar("phone_no"),
        //     "passwd" => password_hash($this->request->getVar("passwd"), PASSWORD_DEFAULT),
        // ];

        if ($validation->run($data, 'em_userval')==FALSE) {

            $response = [
                'status' => 500,
                'error' => true,
                'message' => $this->validator->getErrors(),
                'data' => []
            ];
        } else {

            if ($this->model->insertUser($data)) {

                $response = [
                    'status' => 200,
                    "error" => false,
                    'messages' => 'Successfully, user has been registered',
                    'data' => [$data]
                ];
            } else {

                $response = [
                    'status' => 500,
                    "error" => true,
                    'messages' => 'Failed to create user',
                    'data' => [$data]
                ];
            }
        }

        return $this->respond($response);
    }

    private function getKey()
    {
        return "my_application_secret";
    }

    public function login()
    {
        $rules = [
            "usernm" => "required",
            "passwd" => "required",
        ];

        $messages = [
            "usernm" => [
                "required" => "Username required",
            ],
            "passwd" => [
                "required" => "password is required"
            ],
        ];

        if (!$this->validate($rules, $messages)) {

            $response = [
                'status' => 500,
                'error' => true,
                'message' => $this->validator->getErrors(),
                'data' => []
            ];

            return $this->respondCreated($response);
            
        } else {

            $userdata = $this->model->where("usernm", $this->request->getVar("usernm"))->first();
            $idj = $userdata['id_jabatan'];
            $role = $this->model->getRole($idj);
            $rolename = $role['jabatan'];

            if (!empty($userdata)) {

                if (password_verify($this->request->getVar("passwd"), $userdata['passwd'])) {

                    $key = $this->getKey();

                    $iat = time(); // current timestamp value
                    $nbf = $iat + 10;
                    $exp = $iat + 3600;

                    $payload = array(
                        "iss" => "The_claim",
                        "aud" => "The_Aud",
                        "iat" => $iat, // issued at
                        "nbf" => $nbf, //not before in seconds
                        "exp" => $exp, // expire time in seconds
                        "data" => $userdata,
                    );

                    $token = JWT::encode($payload, $key);

                    $response = [
                        'status' => 200,
                        'error' => false,
                        'messages' => 'User logged In successfully',
                        'role'=>$rolename,
                        'roleid'=>$userdata['id_jabatan'],
                        'id'=>$userdata['id_user'],
                        'token' => $token,
                        'data' => []
                    ];
                    return $this->respondCreated($response);
                } else {

                    $response = [
                        'status' => 500,
                        'error' => true,
                        'messages' => 'Incorrect details',
                        'data' => []
                    ];
                    return $this->respondCreated($response);
                }
            } else {
                $response = [
                    'status' => 500,
                    'error' => true,
                    'messages' => 'User not found',
                    'data' => []
                ];
                return $this->respondCreated($response);
            }
        }
    }

    public function details()
    {
        $key = $this->getKey();
        $authHeader = $this->request->getHeader("Authorization");
        $authHeader = $authHeader->getValue();
        $token = $authHeader;

        try {
            $decoded = JWT::decode($token, $key, array("HS256"));

            if ($decoded) {

                $response = [
                    'status' => 200,
                    'error' => false,
                    'messages' => 'User details',
                    'data' => [
                        'profile' =>[ $decoded
                            ]
                    ]
                ];
                return $this->respondCreated($response);
            }
        } catch (Exception $ex) {
          
            $response = [
                'status' => 401,
                'error' => true,
                'messages' => 'Access denied',
                'data' => [
                "iss"=> " ",
                "aud"=> " ",
                "iat"=> 0,
                "nbf"=> 0,
                "exp"=> 0,
                "data"=> [
                    "id_user"=> " ",
                    "id_jabatan"=> " ",
                    "nama"=> " ",
                    "usernm"=> " ",
                    "passwd"=> " ",
                    "telp"=> " ",
                    "status"=> " ",
                    "id_level"=> " ",
                    "nip"=> " ",
                    "alamat"=> " ",
                    "tempat"=> " ",
                    "tgl_lahir"=> " ",
                    "foto"=> " ",
                    "entry"=> " ",
                    "esign_active"=> " ",
                    "admin_nodin"=> " ",
                    "token"=> " "
                ]
               
                ]
            ];
            return $this->respond($response);
        }
    }

}
