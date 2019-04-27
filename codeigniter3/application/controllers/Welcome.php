<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->output->enable_profiler(TRUE);

        $this->load->helper('url');
        $this->load->model('singer_model');
        $this->load->model('song_model');
        $this->load->helper('url_helper');
        $this->load->database();

        $this->load->library('pagination');
    }
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{


//        $config['base_url'] = base_url();
//        $config['total_rows'] = $this->singer_model->getListWithCache('a', 'name', 'get_count');
//        $config['per_page'] = 10;
//        $config['use_page_numbers'] = true;
//        $config["uri_segment"] = 1;
//
//        $this->pagination->initialize($config);
//        $page = ($this->uri->segment(1)) ? $this->uri->segment(1) : 0;
//
//        var_dump($page);
//        var_dump($this->pagination->create_links());
//        var_dump($this->singer_model->getListWithCache('a', 'name', [$page, $config['per_page']]));
//        var_dump($this->singer_model->findOneBySlugWithCache('alesso')->name);
//        var_dump($this->singer_model->plusHit(1));
//        var_dump($this->db->last_query());

        $config['base_url'] = base_url();
        $config['total_rows'] = $this->song_model->getListWithCache(0, 'a', 'name', 'get_count');
        $config['per_page'] = 2;
        $config['use_page_numbers'] = true;
        $config["uri_segment"] = 1;

        $this->pagination->initialize($config);
        $page = ($this->uri->segment(1)) ? $this->uri->segment(1) : 0;

        var_dump($page);
        var_dump($this->pagination->create_links());
        var_dump($this->song_model->getListWithCache(0, 'a', 'name', [$page, $config['per_page']]));
        var_dump($this->db->last_query());
        var_dump($this->song_model->findOneBySlugsWithCache('radiohead', 'a-wolf-at-the-door'));
        var_dump($this->song_model->plusHit(1));


		$this->load->view('welcome_message');

	}
}
