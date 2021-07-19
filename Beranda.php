<?php 

class Beranda extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Film_model');
	}

	public function index()
	{

		#$data['allFilm'] = $this->Film_model->getAllFilm();
		$data['film'] = $this->Film_model->getAllFilmShow();
		$data['filmToday'] = $this->Film_model->getAllFilmToday();

		#$this->load->view('templates/header');
		#$this->load->view('Beranda/index', $data);
		$this->load->view('Beranda/index',$data);
	}

	public function sedangtayang()
	{
		$data['film'] = $this->Film_model->getAllFilmToday();
		$this->load->view('Beranda/sedangtayang',$data);
	}

	public function listfilm()
	{
		$data['film'] = $this->Film_model->getAllFilmShow();
		$this->load->view('Beranda/listfilm',$data);
	}
}