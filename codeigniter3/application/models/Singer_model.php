<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Singer_model extends CI_Model
{
    public function __construct()
    {
        $this->load->driver('cache', ['adapter' => 'redis', 'backup' => 'file']);
        $this->cache->clean();
        $this->load->database();
    }

    public function getListWithCache($initial = false, $order = 'name', $pages = 14)
    {
        $cache_key  = 'singer_list_'.(string) $initial.'_'.$order;

        if (is_array($pages)) {
            $cache_key  .= '_'.implode('-', $pages);
        }

        $get_cache  = $this->cache->get($cache_key);

        if ($get_cache) {
            return $get_cache;
        }

        $query = $this->db->where('status', 1);

        if (!empty($initial)) {
            if ($initial == '09') {
                $query->like('slug', '0', 'after');

                for ($i = 1; $i <= 9; $i++) {
                    $query->or_like('slug', $i, 'after');
                }
            } else {
                $query->like('slug', $initial, 'after');
            }
        }

        if ($order == 'hit') {
            $query->order_by('hit', 'DESC');
        } elseif ($order == 'name') {
            $query->order_by('slug', 'ASC');
        }

        if ($pages === 'get_count') {
            $result = $query->get('singers')->num_rows();
        } elseif (is_integer($pages) && $pages > 0) {
            $query->limit($pages);
        } elseif (is_array($pages)) {
            $offset = $pages[0] < 1 ? 1 : $pages[0];
            $offset = ($offset - 1) * $pages[1];

            $query->limit($pages[1], $offset);
        }

        if ($pages !== 'get_count') {
            $result = $query->get('singers')->result_object();
        }

        $this->cache->save($cache_key, $result, 360);

        return $result;
    }

    public function findOneBySlugWithCache($slug)
    {
        $cache_key  = 'singer_item_'.$slug;

        $get_cache  = $this->cache->get($cache_key);

        if ($get_cache) {
            return $get_cache;
        }

        $result = $this->db->get_where('singers', ['slug' => $slug])->row();

        $this->cache->save($cache_key, $result, 360);

        return $result;
    }

    public function plusHit($id)
    {
        $this->db->query("UPDATE singers SET hit = hit + 1 WHERE id = ?", [$id]);
    }
}