<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Song_model extends CI_Model
{
    public function __construct()
    {
        $this->load->driver('cache', ['adapter' => 'redis', 'backup' => 'file']);
//        $this->cache->clean();
        $this->load->database();
    }

    public function getListWithCache($singer_id = 0, $initial = false, $order = 'name', $pages = 14)
    {
        $cache_key  = 'song_list_'.$singer_id.'_'.(string) $initial.'_'.$order;

        if (is_array($pages)) {
            $cache_key  .= '_'.implode('-', $pages);
        }

        $get_cache  = $this->cache->get($cache_key);

        if ($get_cache) {
            return $get_cache;
        }

        $query = $this->db->where('songs.status', 1);

        if ($singer_id > 0) {
            $this->db->where('songs.singer_id', $singer_id);
        }

        if (!empty($initial)) {
            if ($initial == '09') {
                $query->like('songs.slug', '0', 'after');

                for ($i = 1; $i <= 9; $i++) {
                    $query->or_like('songs.slug', $i, 'after');
                }
            } else {
                $query->like('songs.slug', $initial, 'after');
            }
        }

        if ($order == 'hit') {
            $query->order_by('songs.hit', 'DESC');
        } elseif ($order == 'name') {
            $query->order_by('songs.slug', 'ASC');
        } elseif ($order == 'new') {
            $query->order_by('songs.created_at', 'DESC');
        } elseif ($order == 'old') {
            $query->order_by('songs.created_at', 'ASC');
        }

        if ($pages === 'get_count') {
            $result = $query->get('songs')->num_rows();

            $this->cache->save($cache_key, $result, 360);

            return $result;
        } elseif (is_integer($pages) && $pages > 0) {
            $query->limit($pages);
        } elseif (is_array($pages)) {
            $offset = $pages[0] < 1 ? 1 : $pages[0];
            $offset = ($offset - 1) * $pages[1];

            $query->limit($pages[1], $offset);
        }

        if ($pages !== 'get_count') {
            $this->db->select('songs.*, singers.name AS singer_name, singers.slug AS singer_slug')
                ->join('singers', 'songs.singer_id = singers.id');

            $result = $query->get('songs')->result_object();
        }

        $this->cache->save($cache_key, $result, 360);

        return $result;
    }

    public function findOneBySlugsWithCache($singer_slug, $song_slug)
    {
        $cache_key  = 'song_item_'.$singer_slug.'_'.$song_slug;

        $get_cache  = $this->cache->get($cache_key);

        if ($get_cache) {
            return $get_cache;
        }

        $result = $this->db
            ->where('songs.slug', $song_slug)
            ->where('singers.slug', $singer_slug)
            ->select('songs.*, singers.name AS singer_name, singers.slug AS singer_slug')
            ->join('singers', 'songs.singer_id = singers.id')->get('songs')->row();

        $this->cache->save($cache_key, $result, 360);

        return $result;
    }

    public function plusHit($id)
    {
        $this->db->query("UPDATE songs SET hit = hit + 1 WHERE id = ?", [$id]);
    }
}