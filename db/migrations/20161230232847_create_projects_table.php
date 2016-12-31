<?php

use Phinx\Migration\AbstractMigration;

class CreateProjectsTable extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up()
    {
        $table = $this->table('projects');
        $table->addColumn('title', 'string')
            ->addColumn('slug', 'string')
            ->addColumn('description', 'text')
            ->addColumn('live_url', 'string', ['null' => true])
            ->addColumn('github_url', 'string', ['null' => true])
            ->addColumn('technologies', 'string', ['null' => true])
            ->addColumn('published_at', 'datetime', ['null' => true])
            ->addColumn('preview', 'string')
            ->addIndex(['slug'], ['unique' => true]);
        $table->save();
    }
}
