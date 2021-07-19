<?php
class AdminJadwal extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Jadwal_model');
		$this->load->library('form_validation');
	}


	public function index()
	{

		$data['judul'] = 'Daftar Jadwal Tayang Film';
		$data['jadwal'] = $this->Jadwal_model->getAllJadwal();
		if ($this->input->post('cari')) {
			$data['jadwal'] = $this->Jadwal_model->cariDataJadwal();
		}
		$this->load->view('templates/adminHeader2', $data);
		$this->load->view('adminjadwal/index', $data);
	}

	public function tambah()
	{
		$data['judul'] = 'Form Tambah Data Penyakit';
		$data['film'] = $this->Jadwal_model->getAllFilm();
		$data['studio'] = $this->Jadwal_model->getAllStudio();
		$data['jam']= array("10:15", "12:35", "14:15", "16:15", "18:35", "20:35");

		
        $this->form_validation->set_rules('idFilm','Nomor id Film','required');
		$this->form_validation->set_rules('noStudio','Nomor Studio','required');
        $this->form_validation->set_rules('jamTayang','Jam Tayang Film','required');
		$this->form_validation->set_rules('tanggal','Tanggal Diputarnya Film','required');


		
        if($this->form_validation->run() == FALSE){
			$this->session->set_flashdata('flash','Data tidak Lengkap');
            $this->load->view('templates/adminHeader', $data);
			$this->load->view('adminjadwal/tambah');
			
        }
        else{
            $dataJ = [
			
                "idFilm" => $this->input->post('idFilm', true),
                "noStudio" => $this->input->post('noStudio', true),
                "jamTayang" => $this->input->post('jamTayang', true),
                "tanggal" => $this->input->post('tanggal', true),
            ];

			if($this->Jadwal_model->isJadwalClear($dataJ) == FALSE){
				$this->session->set_flashdata('flash','Jadwal Tersebut Telah Diambil');
				$this->load->view('templates/adminHeader', $data);
				$this->load->view('adminjadwal/tambah');
				
			}else {
				$this->session->set_flashdata('flash','data berhasil ditambah');
            	$this->Jadwal_model->tambahDataJadwal($dataJ);
            	
            	redirect('adminjadwal');
			}
        }
		
	}

	public function hapus($id)
	{
		
		$this->Jadwal_model->hapusDataJadwal($id);
		$this->session->set_flashdata('flash','Data Jadwal Tayang berhasil dihapus');
		
		redirect('adminjadwal');

	}

	public function ubah($id)
	{
		$data['judul'] = 'Form Ubah Data Penyakit';
		$data['jadwal'] = $this->Jadwal_model->getJadwalById($id);
		$data['film'] = $this->Jadwal_model->getAllFilm();
		$data['studio'] = $this->Jadwal_model->getAllStudio();
		$data['jam']= array("10:15", "12:35", "14:15", "16:15", "18:35", "20:35");

		$this->form_validation->set_rules('idFilm','Nomor id Film','required');
		$this->form_validation->set_rules('noStudio','Nomor Studio','required');
        $this->form_validation->set_rules('jamTayang','Jam Tayang Film','required');
		$this->form_validation->set_rules('tanggal','Tanggal Diputarnya Film','required');

        if($this->form_validation->run() == FALSE){
            $this->load->view('templates/adminHeader', $data);
			$this->load->view('adminjadwal/ubah');
        }
        else{
            $data = [
                
                "idJadwal" =>$this->input->post('idJadwal'),
                "idFilm" => $this->input->post('idFilm', true),
                "noStudio" => $this->input->post('noStudio', true),
                "jamTayang" => $this->input->post('jamTayang', true),
                "tanggal" => $this->input->post('tanggal', true),
            ];

            $this->Jadwal_model->editJadwal($data);
            $this->session->set_flashdata('flash','Data berhasil di update');
            redirect('adminjadwal');
        }
	}

}
