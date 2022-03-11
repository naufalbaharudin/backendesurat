<?php namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class Sk_Workflow extends ResourceController
{
    protected $helpers = ['Notify_helper'];

    protected $modelName = 'App\Models\Sk_Workflowmodel';
    protected $format = 'json';
    

    public function index()
    {
        return $this->respond($this->model->findAll(),200);
    }

    // Read Data
    public function show($id = null)
    {
        //
    }

    // Create data
    public function create()
    {
        $rules = [
            "id_skwf" => "required",
            "id_srt" => "required",
            "s_user" => "required",
            "r_user" => "required",
            "idstatus" => "required",
            "tgl_proses" => "required",
            "tgl_konf" => "required",
            "konf" => "required",
            "baca" => "required",
            "catatan" => "required",
        ];

        $messages = [
            "id_skwf" => [
                "required" => "id must required",
            ],
            "id_srt" => [
                "required" => "id surat must required"
            ],
            "s_user" => [
                "required" => "id sender must required"
            ],
            "r_user" => [
                "required" => "id receiver must required"
            ],
            "idstatus" => [
                "required" => "id status must required"
            ],
            "tgl_proses" => [
                "required" => "tanggal proses must required"
            ],
            "tgl_konf" => [
                "required" => "tanggal konfirmasi must required"
            ],
            "konf" => [
                "required" => "konfirmasi must required"
            ],
            "baca" => [
                "required" => "baca must required"
            ],
            "catatan" => [
                "required" => "catatan must required"
            ],
        ];
        $data = [
            "id_skwf" => $this->request->getPost('id_skwf'),
            "id_srt" => $this->request->getPost('id_srt'),
            "s_user" => $this->request->getPost('s_user'),
            "r_user" => $this->request->getPost('r_user'),
            "idstatus" => $this->request->getPost('idstatus'),
            "tgl_proses" => $this->request->getPost('tgl_proses'),
            "tgl_konf" => $this->request->getPost('tgl_konf'),
            "konf" => $this->request->getPost('konf'),
            "baca" => $this->request->getPost('baca'),
            "catatan" => $this->request->getPost('catatan'),         
        ];
        if (!$this->validate($rules, $messages)) {
            $code = 500;
            $response = [
                'status' => $code,
                'error' => true,
                'message' => $this->validator->getErrors(),
                'data' => []
            ];
        }else{
            $save = $this->model->insertHistory($data);
            if($save)
            {
                $code = 200;
                $response = [
                    'status' => $code,
                    'error' => false,
                    'message' => "Succes sent",
                    'data' => $data,
                ];
                //gettoken
                $token = $this->model->getToken($data['r_user']);
                $b = $token['token'];
                notify($b);
                //end Notifikasi Trigger               
            }
        }
		return $this->respond($response,$code);
    }

    // edit
    public function edit($id = NULL)
    {
        $get = $this->model->getHistory($id);
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
        }else{
            $find = $this->model->getWhere(['id_skwf' => $id])->getResult();
            if($find)
            {
                $data = $this->request->getRawInput();

                $simpan = $this->model->updateHistory($data,$id);
                    if($simpan){
                        $code = 200;
                        $msg = ['message' => 'Updated Surat successfully'];
                        $response = [
                            'status' => $code,
                            'error' => false,
                            'data' => $msg,
                        ];

                            //gettoken
                            $token = $this->model->getToken($data['r_user']);
                            $b = $token['token'];
                            notify($b);
                            //end Notifikasi Trigger    
            }else{
                $code = 404;
                $msg = ['message' => 'No data Found with Id ' .$id];
                $response = [
                    'status' => $code,
                    'error' => false,
                    'data' => $msg,];
                }
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
            $data = $this->model->getWhere(['id_skwf' => $id])->getResult();
            if($data){
                $del = $this->model->deleteHistory($id);
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

}
