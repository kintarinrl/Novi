<?php

class C_Upload extends CI_Controller {

        public function __construct()
        {
                parent::__construct();
                //lakukan load helper [form & url]
                $this->load->helper(array('form','url'));
        }

        public function index()
        {
                $this->load->view('V_Upload_form', array('error' => ' ' ));
        }

       
        public function do_upload()
        {
                $config['upload_path']        = 'C:\xampp\htdocs\TPMOD9\uploads'; //isi dengan nama folder temoat menyimpan gambar
                $config['allowed_types']      =  'jpg|jpeg|gif|png';//isi dengan format/tipe gambar yang diterima
                $config['max_size']           = '10240'; //isi dengan ukuran maksimum yang bisa di upload
                $config['max_width']          = '1366'; //isi dengan lebar maksimum gambar yang bisa di upload
                $config['max_height']         = '1366'; //isi dengan panjang maksimum gambar yang bisa di upload

                $this->load->library('upload', $config);

                //lengkapi kondisi berikut
                if ( ! $this->upload->do_upload('userfile'))
                {
                    $error = array('error' => $this->upload->display_errors());
                    $this->load->view('V_Upload_form', $error);     
                }
                else
                {
                    $data = array('upload' => $this->upload->data());
                    $this->load->view('V_Upload_success', $data);
                }
        }
}
?>