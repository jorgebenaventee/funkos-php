<?php

namespace services;

require_once __DIR__ . '/../../public/services/CategoryService.php';

use models\Category;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;

class CategoryServiceTest extends TestCase
{
    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->stmt = $this->createMock(PDOStatement::class);
        $this->categoryService = new CategoryService($this->pdo);
    }

    public function testGetCategories()
    {
        $expected = array(
            new Category(1, 'test', false),
            new Category(2, 'test2', false),
        );


        $this->pdo->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM categories WHERE is_deleted = false')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('fetchAll')
            ->with(PDO::FETCH_CLASS)
            ->willReturn($expected);

        $this->assertEquals($expected, $this->categoryService->getCategories());
    }

    public function testGetAllCategories()
    {
        $expected = array(
            new Category(1, 'test', false),
            new Category(2, 'test2', false),
        );

        $this->pdo->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM categories')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('fetchAll')
            ->with(PDO::FETCH_CLASS)
            ->willReturn($expected);

        $this->assertEquals($expected, $this->categoryService->getCategories(true));
    }

    public function testCreateCategory()
    {
        $category = new Category(1, 'test', false);
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO categories (name) VALUES (:name)')
            ->willReturn($this->stmt);


        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->assertNull($this->categoryService->createCategory('test'));
    }

    public function testHasFunkos()
    {
        $uuid = '9aeef53e-c90e-11ee-8a10-0242ac130002';
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT COUNT(*) FROM funkos WHERE category_id = :id')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('bindValue')
            ->with('id', $uuid);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->stmt->expects($this->once())
            ->method('fetchColumn')
            ->willReturn(1);

        $this->assertTrue($this->categoryService->hasFunkos($uuid));
    }

    public function testDeleteCategory()
    {
        $uuid = '9aeef53e-c90e-11ee-8a10-0242ac130002';
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM categories WHERE id = :id')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('bindValue')
            ->with('id', $uuid);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->assertNull($this->categoryService->deleteCategory($uuid));
    }

    public function testSoftDelete()
    {
        $uuid = '9aeef53e-c90e-11ee-8a10-0242ac130002';
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE categories SET is_deleted = true WHERE id = :id')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('bindValue')
            ->with('id', $uuid);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->assertNull($this->categoryService->softDelete($uuid));
    }

    public function testRestore()
    {
        $uuid = '9aeef53e-c90e-11ee-8a10-0242ac130002';
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE categories SET is_deleted = false WHERE id = :id')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('bindValue')
            ->with('id', $uuid);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->assertNull($this->categoryService->restore($uuid));
    }


    public function testGetCategoryById()
    {
        $uuid = '9aeef53e-c90e-11ee-8a10-0242ac130002';
        $expected = new Category($uuid, 'test', false);
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM categories WHERE id = :id')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('bindValue')
            ->with('id', $uuid);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->stmt->expects($this->once())
            ->method('setFetchMode')
            ->with(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Category::class);

        $this->stmt->expects($this->once())
            ->method('fetchObject')
            ->willReturn($expected);

        $this->assertEquals($expected, $this->categoryService->getCategoryById($uuid));
    }

    public function testUpdateCategory()
    {
        $uuid = '9aeef53e-c90e-11ee-8a10-0242ac130002';
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE categories SET name = :name WHERE id = :id')
            ->willReturn($this->stmt);

        $matcher
            = $this->exactly(2);
        $this->stmt->expects($matcher)
            ->method('bindValue')
            ->willReturn(
                true
            );


        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->assertNull($this->categoryService->updateCategory($uuid, 'test'));
    }

    public function testExists()
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT COUNT(*) FROM categories WHERE name = :name')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('bindValue')
            ->with('name', 'test');

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->stmt->expects($this->once())
            ->method('fetchColumn')
            ->willReturn(1);

        $this->assertTrue($this->categoryService->exists('test'));
    }
}
