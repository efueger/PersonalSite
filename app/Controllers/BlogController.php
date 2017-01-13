<?php

namespace App\Controllers;

use App\Models\Blog\Post;
use App\Models\Blog\PostMapper;
use Cocur\Slugify\Slugify;
use Slim\Exception\NotFoundException;
use Slim\Http\Request;
use Slim\Http\Response;

class BlogController extends BaseController
{
    public function index(Request $request, Response $response)
    {
        $mapper = new PostMapper($this->db);
        $posts = $mapper->getPosts();

        return $this->view->render($response, 'posts/index.twig', compact('posts'));
    }

    public function show(Request $request, Response $response, array $args)
    {
        $slug = (string)$args['slug'];
        $mapper = new PostMapper($this->db);
        $post = $mapper->getPostBySlug($slug);

        if (!$post) {
            throw new NotFoundException($request, $response);
        }

        return $this->view->render($response, 'posts/show.twig', ['post' => $post]);
    }

    public function new(Request $request, Response $response)
    {
        return $this->view->render($response, 'admin/blog/posts/new.twig');
    }

    public function store(Request $request, Response $response)
    {
        $params = $request->getParams();
        $slugify = new Slugify();
        $slug = $slugify->slugify($params['title']);
        $postData = [
            'title' => $params['title'],
            'slug' => $slug,
            'content' => $params['content'],
            'published_at' => $params['published_at'],
        ];

        $post = new Post($postData);
        $postMapper = new PostMapper($this->db);
        $postMapper->save($post);

        return $response->withRedirect($this->router->pathFor('admin.blog.published'));
    }

    public function listPublished(Request $request, Response $response)
    {
        $mapper = new PostMapper($this->db);
        $posts = $mapper->getPublishedPosts();

        return $this->view->render($response, 'admin/blog/posts/published.twig', compact('posts'));
    }
}
