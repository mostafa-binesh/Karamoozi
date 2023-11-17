<?php

namespace App\Repositories;

use App\Http\Resources\NewsResource;
use App\ModelFilters\NewsFilter;
use App\Models\News;
use Illuminate\Http\Request;


class NewsRepo
{
    private $news;

    public function __construct(News $news)
    {
        $this->news = $news;
    }

    public function getById($id)
    {
        $news = $this->news->find($id);
        if (!isset($news->id)) {
            return false;
        }
        return NewsResource::make($news);
    }

    public function create($news)
    {
        $this->news->create($news);
    }

    public function update($news_req, $news)
    {
        $news->title = $news_req->title;
        $news->body = $news_req->body;
        $news->save();

    }

    public function delete($id)
    {
        return $this->news->destroy($id);
    }

    public function getAll()
    {
        return $this->news->get()->all();
    }

    public function paginat(Request $req)
    {
        return News::filter($req->all(), NewsFilter::class)->cpagination($req, NewsResource::class);
    }
}
