<?php

use Phinx\Migration\AbstractMigration;

class CreatePostsTable extends AbstractMigration
{

    public function up()
    {
        $table = $this->table('posts');
        $table->addColumn('title', 'string', ['limit' => 200])
            ->addColumn('slug', 'string', ['limit' => 200])
            ->addColumn('content', 'text')
            ->addColumn('published', 'datetime', ['null' => true])
            ->addIndex(['slug'], ['unique' => true])
            ->save();
    }
}
