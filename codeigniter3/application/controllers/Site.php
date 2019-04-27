<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Site extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->output->enable_profiler(TRUE);

        $this->load->database();

        $this->load->helper('url');
        $this->load->helper('url_helper');
        $this->load->helper('site_helper');

        $this->load->model('singer_model');
        $this->load->model('song_model');

        $this->load->library('pagination');
    }

    public function home()
    {
        $songs  = $this->song_model->getListWithCache(0, 'a', 'name', 6);

        $this->load->view('home', ['songs' => $songs]);
    }

    public function singers()
    {
        $letters    = getLetters();

        $l      = $this->uri->segment(2);
        $page   = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        if (isset($letters[$l])) {
            if ($l == 'hit') {
                $initial    = false;
                $order      = 'hit';
                $title      = 'Hit Singers';
            } else {
                $initial    = $l;
                $order      = 'name';
                $title      = 'Singers Beginning with '.$letters[$l];
            }

            $config['base_url']         = base_url().'singers/'.$l;
            $config['total_rows']       = $this->singer_model->getListWithCache($initial, $order, 'get_count');
            $config['per_page']         = 20;
            $config['use_page_numbers'] = true;
            $config["uri_segment"]      = 3;

            $this->pagination->initialize($config);

            $singers    = $this->singer_model->getListWithCache($initial, $order, [$page, $config['per_page']]);

            $this->load->view('singers', [
                'title'         => $title,
                'singers'       => $singers,
                'page_links'    => $this->pagination->create_links()
            ]);

        } else {
            $this->load->view('errors/html/error_404', ['heading' => 'Not Found', 'message' => '404']);
        }
    }

    public function songs()
    {
        $letters    = getLetters();

        $l      = $this->uri->segment(2);
        $page   = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        if (isset($letters[$l])) {
            if ($l == 'hit') {
                $initial    = false;
                $order      = 'hit';
                $title      = 'Hit Songs';
            } else {
                $initial    = $l;
                $order      = 'name';
                $title      = 'Songs Beginning with '.$letters[$l];
            }

            $config['base_url']         = base_url().'songs/'.$l;
            $config['total_rows']       = $this->song_model->getListWithCache(0, $initial, $order, 'get_count');
            $config['per_page']         = 40;
            $config['use_page_numbers'] = true;
            $config["uri_segment"]      = 3;

            $this->pagination->initialize($config);

            $songs    = $this->song_model->getListWithCache(0, $initial, $order, [$page, $config['per_page']]);

            $this->load->view('songs', [
                'title'         => $title,
                'songs'         => $songs,
                'page_links'    => $this->pagination->create_links()]);

        } else {
            $this->load->view('errors/html/error_404', ['heading' => 'Not Found', 'message' => '404']);
        }
    }

    public function singer_songs()
    {
        $singer_url     = $this->uri->segment('1');
        $singer_slug    = preg_replace('/\b-songs$/', '', $singer_url);

        if (!empty($singer_slug)) {
            $singer = $this->singer_model->findOneBySlugWithCache($singer_slug);

            if ($singer) {
                $config['base_url']         = base_url().$singer_slug.'-songs';
                $config['total_rows']       = $this->song_model->getListWithCache($singer->id, false, 'new', 'get_count');
                $config['per_page']         = 20;
                $config['use_page_numbers'] = true;
                $config["uri_segment"]      = 2;

                $page   = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;

                $this->pagination->initialize($config);

                $songs    = $this->song_model->getListWithCache($singer->id, false, 'new', [$page, $config['per_page']]);

                $this->load->view('singer_songs', [
                    'singer'        => $singer,
                    'songs'         => $songs,
                    'page_links'    => $this->pagination->create_links()
                ]);

                return true;
            }
        }

        $this->load->view('errors/html/error_404', ['heading' => 'Not Found', 'message' => '404']);

        return false;
    }

    public function song_view()
    {
        $singer_slug    = $this->uri->segment('1');
        $song_slug      = $this->uri->segment('2');
        $song_slug      = preg_replace('/\b-lyrics$/', '', $song_slug);

        if (!empty($singer_slug) && !empty($song_slug)) {
            $song   = $this->song_model->findOneBySlugsWithCache($singer_slug, $song_slug);

            if ($song) {
                $this->load->view('song_view', ['song' => $song]);

                return true;
            }
        }

        $this->load->view('errors/html/error_404', ['heading' => 'Not Found', 'message' => '404']);

        return false;
    }


}