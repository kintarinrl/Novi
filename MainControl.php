<?php
class MainControl extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Admin_model');
        $this->load->model('User_model');
        $this->load->model('Film_model');
        $this->load->model('Order_model');
        $this->load->model('Jadwal_model');
        $this->load->model('Kursi_model');
        $this->load->model('Studio_model');
		$this->load->library('form_validation');
        $this->load->library('session');
	}


	public function index()
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

                $user = $this->User_model->getUser($data);
                $this->session->set_userdata($user);

                header('Location: http://localhost/WebsiteNofi/index.php/MainControl/berandaUser/');
                #$this->load->view('adminjadwal/index');
            }else {
                $this->session->set_flashdata('flash','Username/Password Salah');
                $this->load->view('maincontrol/login');    
            }
        }
	}

    public function berandaUser()
    {
        $data['film'] = $this->Film_model->getAllFilmShow();
		$data['filmToday'] = $this->Film_model->getAllFilmToday();
        $this->load->view('maincontrol/berandaUser',$data);
    }

    public function sedangtayangUser()
	{
		$data['film'] = $this->Film_model->getAllFilmToday();
		$this->load->view('maincontrol/sedangtayangUser',$data);
	}

    public function listfilmUser()
	{
		$data['film'] = $this->Film_model->getAllFilmShow();
		$this->load->view('maincontrol/listfilmUser',$data);
	}

    public function detail($id)
    {
        $data['film'] = $this->Film_model->getFilmById($id);
        $this->load->view('maincontrol/detail',$data);
    }

    public function userprofil()
    {
        $this->load->view('maincontrol/userprofil');
    }

    public function historyorder()
    {
        $data['order'] = $this->Order_model-> getOrder($_SESSION['idUser']);
        $this->load->view('maincontrol/historyorder', $data);
    }

    public function historydetail($idOrder)
    {
        $data['judul'] = 'Form Detail History';
        $data['order'] = $this->Order_model->getOrderByIdOrder($idOrder);
        $this->load->view('maincontrol/historydetail', $data);
    }

    public function ordertiket($idFilm)
    {
        $data['Movie'] = $this->Film_model->getFilmById($idFilm);
        $data['jadwalTersedia'] = $this->Jadwal_model->getJadwalByFilm($idFilm); 
        #$this->load->view('maincontrol/ordertiket', $data);


        $this->form_validation->set_rules('idJadwalTiket','Jadwal Tiket','required');

		
        if($this->form_validation->run() == FALSE){
            $this->load->view('maincontrol/ordertiket', $data);   
        }
        else{  
            $data = $this->input->post('idJadwalTiket', true);
            $link = 'Location: http://localhost/WebsiteNofi/index.php/MainControl/ordertiketkursi/'.$data;
            header($link);
           

        }
    }

    public function ordertiketkursi($idJadwal)
    {
        $data['tiket'] = $this->Jadwal_model->getAllJadwalByID($idJadwal);
        $data['kursi'] = $this->Kursi_model->getKursiKosong($idJadwal);
         
        
        $this->form_validation->set_rules('NOKURSI','No Kursi','required');

		
        if($this->form_validation->run() == FALSE){
            $this->load->view('maincontrol/ordertiketkursi', $data);   
        }
        else{  
            $user = $this->session->userdata();
            $order = [
                "idJadwal" => $idJadwal,
                "idUser" => $user['idUser'],
                "noKursi" => $this->input->post('NOKURSI', true),
                "tglOrder" => date("Y-m-d"),
                "status" => 'Lunas',
            ];
            $this->Order_model->tambahDataOrder($order);
            redirect('maincontrol/berandauser');
        }
        
    }
}
