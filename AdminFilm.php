<?php
class AdminFilm extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form','url'));
		$this->load->model('Film_model');
		$this->load->library('form_validation');
	}


	public function index()
	{

		$data['judul'] = 'Daftar Film';
		$data['film'] = $this->Film_model->getAllFilm();
		if ($this->input->post('cari')) {
			$data['film'] = $this->Film_model->cariDataFilm();
		}
		$this->load->view('templates/adminHeader2', $data);
		$this->load->view('adminfilm/index', $data);
	}

	public function tambah()
	{
		$data['judul'] = 'Form Tambah Data Film';
		$data['film'] = $this->Film_model->getAllFilm();
		
		$config['upload_path']        = 'C:\xampp\htdocs\WebsiteNofi\CoverFilm'; //isi dengan nama folder temoat menyimpan gambar
		$config['allowed_types']      =  'jpg|jpeg|gif|png';//isi dengan format/tipe gambar yang diterima
		$config['max_size']           = '10240'; //isi dengan ukuran maksimum yang bisa di upload
		$config['max_width']          = '1366'; //isi dengan lebar maksimum gambar yang bisa di upload
		$config['max_height']         = '1366'; //isi dengan panjang maksimum gambar yang bisa di upload

		$this->load->library('upload', $config);
		
		$this->form_validation->set_rules('judul','Judul Film','required');
        $this->form_validation->set_rules('durasi','Durasi Film','required');
        $this->form_validation->set_rules('tahun','Tahun Rilis Film','required');
        $this->form_validation->set_rules('sinopsis','Sinopsis Film','required');
        $this->form_validation->set_rules('tampilkan','Tampilkan Film di Beranda ?','required');
		#$this->form_validation->set_rules('gbrCover','Gambar Cover Film','required');

		
        if($this->form_validation->run() == FALSE || ! $this->upload->do_upload('userfile')){
			$error = array('error' => $this->upload->display_errors());
			$this->load->view('templates/adminHeader', $data);
			$this->load->view('adminfilm/tambah', $error);
        }
        else{
            $imgdata = $this->upload->data();

            $data = [
			
                "judul" => $this->input->post('judul', true),
                "durasi" => $this->input->post('durasi', true),
                "tahun" => $this->input->post('tahun', true),
                "sinopsis" => $this->input->post('sinopsis', true),
                "tampilkan" => $this->input->post('tampilkan', true),
                "gbrCover" => $imgdata['file_name'],
            ];

            $this->Film_model->tambahDataFilm($data);
            $this->session->set_flashdata('flash','data berhasil ditambah');
            redirect('adminfilm');
        }
		
	}

	public function hapus($noRS)
	{

		if($this->Film_model->isClear($noRS)) {
			$this->Film_model->hapusDataFilm($noRS);
		    $this->session->set_flashdata('flash','Data FILM berhasil dihapus');
		}
		else {
		    $this->session->set_flashdata('flash','Data JADWAL harus dihapus terlebih dahulu');
		}
		
		redirect('adminfilm');

	}

	public function ubah($noRS)
	{
		$data['judul'] = 'Form Ubah Data FILM';
		$data['film'] = $this->Film_model->getFilmById($noRS);

		$this->form_validation->set_rules('judul','Judul Film','required');
        $this->form_validation->set_rules('durasi','Durasi Film','required');
        $this->form_validation->set_rules('tahun','Tahun Rilis Film','required');
        $this->form_validation->set_rules('sinopsis','Sinopsis Film','required');
        $this->form_validation->set_rules('tampilkan','Tampilkan Film di Beranda ?','required');
		#$this->form_validation->set_rules('gbrCover','Gambar Cover Film','required');
        

        if($this->form_validation->run() == FALSE){
            $this->load->view('templates/adminHeader', $data);
			$this->load->view('adminfilm/ubah');
        }
        else{
            $data = [
                
                "idFilm" => $this->input->post('idFilm'),
                "judul" => $this->input->post('judul', true),
                "durasi" => $this->input->post('durasi', true),
                "tahun" => $this->input->post('tahun', true),
                "sinopsis" => $this->input->post('sinopsis', true),
                "tampilkan" => $this->input->post('tampilkan', true),
                #"gbrCover" => $this->input->post('gbrCover', true),
            ];

            $this->Film_model->editFilm($data);
            $this->session->set_flashdata('flash','Data berhasil di update');
            redirect('adminfilm');
        }
	}

    public function detail($id)
	{
		$data['film'] = $this->Film_model->getFilmById($id);
		$this->load->view('templates/adminHeader', $data);
		$this->load->view('adminfilm/detail');
	}

}
