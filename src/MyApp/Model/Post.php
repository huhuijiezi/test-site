<?php
namespace MyApp\Model;

use Silex\Application;
use Doctrine\DBAL\Connection;

/**
 * Class Post
 */
class Post {

	/**
	 * @var Connection
	 */
	private $db;

	public function __construct(Connection $db) {

		$this->db = $db;
	}

	public function getPostById($id) {

		return $this->db->fetchAll('SELECT * FROM posts WHERE post_id = '.$id);
	}

	/**
	 * @return array
	 */
	public function getAllPosts() {

		return $this->db->fetchAll('SELECT * FROM posts order by post_id desc');
	}

	/**
	 * @return array
	 */
	public function getRecentPosts() {

		return $this->db->fetchAll('SELECT post_id, title FROM posts ORDER BY time DESC lIMIT 5');
	}

	/**
	 * @return array
	 */
	public function getArchives() {

		$query = 'SELECT 
  			MONTHNAME(FROM_UNIXTIME(time)) as monthName, 
  			MONTH(FROM_UNIXTIME(time)) as month,
  			YEAR(FROM_UNIXTIME(time)) as year, 
  			COUNT(*) as count
			FROM posts
			GROUP BY 
  			month, monthName, year';
		return $this->db->fetchAll($query);
	}

	/**
	 * @param $year
	 * @param $month
	 * @return array
	 */
	public function getArchivesByYearAndMonth($year, $month) {

		$query = 'SELECT * FROM posts where MONTH(FROM_UNIXTIME(time))  = '.$month.' And YEAR(FROM_UNIXTIME(time)) ='.$year.' ORDER BY time DESC';
		return $this->db->fetchAll($query);
	}

	/**
	 * @param $title
	 * @param $content
	 * @return int
	 */
	public function savePost($title, $content){

		return $this->db->insert('posts',array('title' => $title, 'content' => $content, 'time' => time()));
	}

}