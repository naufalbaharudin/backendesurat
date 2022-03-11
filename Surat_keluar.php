<?php namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class Surat_keluar extends ResourceController
{
    protected $helpers = ['Notify_helper'];

    protected $modelName = 'App\Models\Surat_keluarModel';
    protected $format = 'json';
    

    public function index()
    {
        return $this->respond($this->model->findAll(),200);
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
            $get = $this->model->getSurat($id);
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
            'tahun' => $this->request->getPost('tahun'),
            'satker' => $this->request->getPost('satker'),
            'rank' => $this->request->getPost('rank'),
            'id_srt' => $this->request->getPost('id_srt'),
            'no_agenda' => $this->request->getPost('no_agenda'),
            'no_surat' => $this->request->getPost('no_surat'),
            'tgl_surat' => $this->request->getPost('tgl_surat'),
            'ttd' => $this->request->getPost('ttd'),
            'kepada' => $this->request->getPost('kepada'),
            'perihal' => $this->request->getPost('perihal'),
            'id_kat' => $this->request->getPost('id_kat'),
            'id_sifat' => $this->request->getPost('id_sifat'),
            'tgl_deadline' => $this->request->getPost('tgl_deadline'),
            'tgl_kirim' => $this->request->getPost('tgl_kirim'),
            'w_user' => $this->request->getPost('w_user'),
            'state' => $this->request->getPost('state'),
            'ket' => $this->request->getPost('ket'),
            'oleh' => $this->request->getPost('oleh'),
            'arsip' => $this->request->getPost('arsip'),
            // '_start' => $this->request->getPost('_start'),
            // '_finish' => $this->request->getPost('_finish'),

            // File Uploaded
            //'file_surat' => $this->request->getFile('file_surat'),
            'file_surat' => $this->request->getPost('file_surat'),
            
        ];

        if($validation->run($data, 'srtval') == FALSE){
            $code = 500;
            $response = [
                'status' => $code,
                'error' => true,
                'data' => $validation->getErrors(),
            ];
        }else{
            $save = $this->model->insertSurat($data);
            if($save)
            {
                $code = 200;
                $response = [
                    'status' => $code,
                    'error' => false,
                    'data' => $data,
                ];
                //gettoken
                $token = $this->model->getToken($data['w_user']);
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
        $get = $this->model->getSurat($id);
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
        $validation =  \Config\Services::validation();

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
            $find = $this->model->getWhere(['id_srt' => $id])->getResult();
            if($find)
            {
                $data = $this->request->getRawInput();

                $simpan = $this->model->updateSurat($data,$id);
                    if($simpan){
                        $code = 200;
                        $msg = ['message' => 'Updated Surat successfully'];
                        $response = [
                            'status' => $code,
                            'error' => false,
                            'data' => $msg,
                        ];

                            //gettoken
                            $token = $this->model->getToken($data['w_user']);
                            $b = $token['token'];
                            notify($b);
                            //end Notifikasi Trigger    
                                        }
           

            }else{
                $code = 404;
                $msg = ['message' => 'No data Found with Id ' .$id];
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
            $data = $this->model->getWhere(['id_srt' => $id])->getResult();
            if($data){
                $del = $this->model->deleteSurat($id);
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
    public function priority($id)
        
    
    public function byuser($id=null)
    {
     return $this->model->getWhere(['w_user' => $id])->getResult();
    }



}
