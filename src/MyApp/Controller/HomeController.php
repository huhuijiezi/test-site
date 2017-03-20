<?php
namespace MyApp\Controller;

use Silex\Application;
use MyApp\Model\Post;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class HomeController
 *
 * @package MyApp\Controller
 */
class HomeController {

	/**
	 * @param Application $app
	 * @return mixed
	 */
	public static function getIndex(Application $app) {

		$db = new Post($app['db']);
		$allPost = $db->getAllPosts();
		$recentPosts = $db->getRecentPosts();
		$archives = $db->getArchives();


		return $app['twig']->render('home.html.twig', array('posts' => $allPost, 'recentPosts' => $recentPosts, 'archives' => $archives));
	}

	/**
	 * @param Application $app
	 * @param $year
	 * @param $month
	 * @return mixed
	 */
	public static function getArchive(Application $app, $year, $month) {

		$db = new Post($app['db']);

		$archivePosts = $db->getArchivesByYearAndMonth($year, $month);
		$recentPosts = $db->getRecentPosts();
		$archives = $db->getArchives();

		return $app['twig']->render('home.html.twig', array('posts' => $archivePosts, 'recentPosts' => $recentPosts, 'archives' => $archives));
	}

	/**
	 * @param Application $app
	 * @param $postId
	 * @return mixed
	 */
	public static function getPostById(Application $app, $postId) {

		$db = new Post($app['db']);

		$post = $db->getPostById($postId);
		$recentPosts = $db->getRecentPosts();
		$archives = $db->getArchives();

		return $app['twig']->render('post.html.twig', array('post' => $post, 'recentPosts' => $recentPosts, 'archives' => $archives));
	}

	/**
	 * @param Application $app
	 * @return mixed
	 */
	public static function getSubmitForm(Application $app) {

		$db = new Post($app['db']);
		$recentPosts = $db->getRecentPosts();
		$archives = $db->getArchives();

		return $app['twig']->render('submit.html.twig', array('title' => null, 'content' => null, 'recentPosts' => $recentPosts, 'archives' => $archives));
	}

	/**
	 * @param Application $app
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public static function postSubmitForm(Application $app, Request $request) {

		$db = new Post($app['db']);
		$postId = null;
		$title = $request->get('title');
		$content = $request->get('content');

		if (!isset($title) && !isset($content)) {
			$postId = $db->savePost($title, $content);
		}

		if ($postId) {
			return $app->redirect($app["url_generator"]->generate("homepage"));

		} else {
			return $app->redirect($app["url_generator"]->generate("submitForm"));
		}
	}


}