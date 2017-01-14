<?php

namespace App\Controllers;

use App\Models\Blog\Post;
use App\Models\Blog\PostMapper;
use Cocur\Slugify\Slugify;
use Slim\Exception\NotFoundException;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class BlogController
 * @package App\Controllers
 */
class BlogController extends BaseController
{
    /**
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function index(Request $request, Response $response)
    {
        $mapper = new PostMapper($this->db);
        $posts = $mapper->getPosts();

        return $this->view->render($response, 'posts/index.twig', compact('posts'));
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     * @throws NotFoundException
     */
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

    /**
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function new(Request $request, Response $response)
    {
        return $this->view->render($response, 'admin/blog/posts/new.twig');
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function store(Request $request, Response $response)
    {
        $params = $request->getParams();
        $slugify = new Slugify();
        $slug = $slugify->slugify($params['title']);
        $date = $params['published_at'];
        if (empty($date)) {
            $date = null;
        }

        $postData = [
            'title' => $params['title'],
            'slug' => $slug,
            'content' => $params['content'],
            'published_at' => $date,
        ];

        $post = new Post($postData);
        $postMapper = new PostMapper($this->db);
        $postMapper->save($post);

        return $response->withRedirect($this->router->pathFor('admin.blog.published'));
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function listPublished(Request $request, Response $response)
    {
        $mapper = new PostMapper($this->db);
        $posts = $mapper->getPublishedPosts();

        return $this->view->render($response, 'admin/blog/posts/published.twig', compact('posts'));
    }

    public function listDraft(Request $request, Response $response)
    {
        $mapper = new PostMapper($this->db);
        $posts = $mapper->getDraftPosts();

        return $this->view->render($response, 'admin/blog/posts/draft.twig', compact('posts'));
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function destroy(Request $request, Response $response, array $args)
    {
        $slug = (string)$args['slug'];
        $mapper = new PostMapper($this->db);
        $mapper->delete($slug);

        $this->flash->addMessage('success', 'Post successfully deleted');
        return $response->withRedirect($this->router->pathFor('admin.blog.published'));
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     * @throws NotFoundException
     */
    public function edit(Request $request, Response $response, array $args)
    {
        $slug = (string)$args['slug'];
        $mapper = new PostMapper($this->db);
        $post = $mapper->getPostBySlug($slug);

        if (!$post) {
            throw new NotFoundException($request, $response);
        }

        return $this->view->render($response, 'admin/blog/posts/edit.twig', compact('post'));
    }

    public function update(Request $request, Response $response, array $args)
    {
        $params = $request->getParams();
        $slug = (string)$args['slug'];
        $mapper = new PostMapper($this->db);
        $post = $mapper->getPostBySlug($slug);
        $date = $params['published_at'];
        if (empty($date)) {
            $date = null;
        }

        $post->setTitle($params['title']);
        $post->setContent($params['content']);
        $post->setPublishedAt($date);

        $mapper->update($post);

        $this->flash->addMessage('success', 'Post Successfully Updated');
        return $response->withRedirect($this->router->pathFor('admin.blog.published'));
    }
}
