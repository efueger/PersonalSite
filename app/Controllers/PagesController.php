<?php

namespace App\Controllers;

use App\Models\Blog\PostMapper;
use App\Models\Portfolio\ProjectMapper;
use Slim\Http\Request;
use Slim\Http\Response;

class PagesController extends BaseController
{
    public function index(Request $request, Response $response)
    {
        return $this->view->render($response, 'index.twig');
    }

    public function adminIndex(Request $request, Response $response)
    {
        $postMapper = new PostMapper($this->db);
        $publishedPosts = $postMapper->countPublishedPosts();
        $draftPosts = $postMapper->countDraftPosts();

        $projectMapper = new ProjectMapper($this->db);
        $publishedProjects = $projectMapper->countPublishedProjects();
        $draftProjects = $projectMapper->countDraftProjects();

        return $this->view->render(
            $response,
            'admin/index.twig',
            compact(
                'publishedPosts',
                'draftPosts',
                'publishedProjects',
                'draftProjects'
            )
        );
    }
}
