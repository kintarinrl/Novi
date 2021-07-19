<?php 

class MergeControl extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Film_model');
        $this->load->model('Admin_model');
        $this->load->model('User_model');
		$this->load->library('form_validation');
        $this->load->helper('url');
	}

	public function index()
	{

		#$data['allFilm'] = $this->Film_model->getAllFilm();
		#$data['showFilm'] = $this->Film_model->getAllFilmShow();

		#$this->load->view('templates/header');
		#$this->load->view('Beranda/index', $data);
		$this->load->view('mergeControl/index');
	}

    public function login()
	{
        $this->form_validation->set_rules('username','Username/Admin','required');
        $this->form_validation->set_rules('password','Password','required');

		
        if($this->form_validation->run() == FALSE){
            $this->session->set_flashdata('flash','Tolong Lengkapi Data Login');
            $this->load->view('maincontrol/login');
            #$this->load->view('templates/header');
            
        }
        else{  
            $data = [
			
                "userName" => $this->input->post('username', true),
                "password" => $this->input->post('password', true),
            ];

            if($this->Admin_model->adminLogin($data) == TRUE){
                header('Location: http://localhost/WebsiteNofi/index.php/AdminFilm');
                #$this->load->view('adminfilm/index');
             }else if($this->User_model->userLogin($data) == TRUE){
                header('Location: http://localhost/WebsiteNofi/index.php/Beranda');
                #$this->load->view('adminjadwal/index');
            }else {
                $this->session->set_flashdata('flash','Username/Password Salah');
                $this->load->view('maincontrol/index');    
            }
        }
	}
}