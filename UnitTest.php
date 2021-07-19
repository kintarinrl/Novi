<?php
class UnitTest extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
        $this->load->library('unit_test');
		$this->load->model('Film_model');
		$this->load->model('Admin_model');
	}


	public function index()
	{
       
		$idFilm = '2';
		$expected_result2 = TRUE;
		$test_name2 = 'Uji Coba apakah Film Clear dari jadwal';
		$test2 = $this->Film_model->isClear($idFilm);
		$this->unit->run($test2, $expected_result2, $test_name2);
		
		$idFilm2 = 'FILM000001';
		$expected_result3 = FALSE;
		$test_name3 = 'Uji Coba apakah Film Tidak Clear dari jadwal';
		$test3 = $this->Film_model->isClear($idFilm2);
		$this->unit->run($test3, $expected_result3, $test_name3);

		

		$data['userName'] = 'AsepBalon';
		$data['password'] = 'balonkuada5';
		$test_name4 = 'TEST LOGIN ADMIN';
		$result = TRUE;
		$test4 = $this->Admin_model->adminLogin($data);
		$this->unit->run($test4, $result, $test_name4);
		
		echo $this->unit->report();	
	}
}	