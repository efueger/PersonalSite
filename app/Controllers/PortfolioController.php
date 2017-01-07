<?php

namespace App\Controllers;

use App\Portfolio\ProjectEntity;
use App\Portfolio\ProjectMapper;
use Cocur\Slugify\Slugify;
use Slim\Exception\NotFoundException;
use Slim\Http\Request;
use Slim\Http\Response;

class PortfolioController extends BaseController
{
    public function index(Request $request, Response $response)
    {
        $mapper = new ProjectMapper($this->db);
        $projects = $mapper->getProjects();

        return $this->view->render($response, 'projects/index.twig', compact('projects'));
    }

    public function show(Request $request, Response $response, array $args)
    {
        $slug = (string)$args['slug'];
        $mapper = new ProjectMapper($this->db);
        $project = $mapper->getProjectBySlug($slug);

        if (!$project) {
            throw new NotFoundException($request, $response);
        }

        return $this->view->render($response, 'projects/show.twig', compact('project'));
    }

    public function new(Request $request, Response $response)
    {
        return $this->view->render($response, 'projects/new.twig');
    }

    public function store(Request $request, Response $response)
    {
        $params = $request->getParams();

        $files = $request->getUploadedFiles();
        $previewImage = $files['preview'];
        $previewImageFileName = $previewImage->getClientFilename();
        $previewImage->moveTo(__DIR__ . "/../public/img/projects/{$previewImageFileName}");

        $slugify = new Slugify();
        $slug = $slugify->slugify($params['title']);
        $projectData = [
            'title' => $params['title'],
            'slug' => $slug,
            'description' => $params['description'],
            'live_url' => $params['live_url'],
            'github_url' => $params['github_url'],
            'technologies' => $params['technologies'],
            'preview' => $previewImageFileName,
            'published_at' => $params['published_at'],
        ];

        $project = new ProjectEntity($projectData);
        $projectMapper = new ProjectMapper($this->db);
        $projectMapper->save($project);

        return $response->withRedirect($this->router->pathFor('portfolio.index'));
    }
}
