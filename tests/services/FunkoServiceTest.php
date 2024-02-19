<?php

namespace services;

require_once __DIR__ . '/../../public/services/FunkoService.php';

use models\Funko;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;

class FunkoServiceTest extends TestCase
{

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->stmt = $this->createMock(PDOStatement::class);
        $this->funkoService = new FunkoService($this->pdo);
    }

    public function testGetFunkos()
    {
        $funko1 = new Funko();
        $funko2 = new Funko();
        $expected = array($funko1, $funko2);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT f.*, c.name as category_name FROM funkos f JOIN categories c ON f.category_id = c.id order by f.id')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn($expected);

        $this->stmt->expects($this->once())
            ->method('setFetchMode')
            ->with(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Funko::class)
            ->willReturn(true);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->assertEquals($expected, $this->funkoService->getFunkos());
    }

    public function testGetFunkosWithSearch()
    {
        $expected = array(
            new Funko(),
            new Funko(),
        );

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT f.*, c.name as category_name FROM funkos f JOIN categories c ON f.category_id = c.id WHERE f.name LIKE :search collate utf8mb4_general_ci order by f.id')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('bindValue')
            ->with('search', '%test%')
            ->willReturn(true);

        $this->stmt->expects($this->once())
            ->method('execute');

        $this->stmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn($expected);

        $this->stmt->expects($this->once())
            ->method('setFetchMode')
            ->with(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Funko::class)
            ->willReturn(true);
        $this->assertEquals($expected, $this->funkoService->getFunkos('test'));
    }

    public function testRemove()
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM funkos WHERE id = :id')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('bindValue')
            ->with('id', 1)
            ->willReturn(true);

        $this->stmt->expects($this->once())
            ->method('execute');

        $this->funkoService->remove(1);
    }



    public function testCreate()
    {
        $funko = new Funko(1, 'test', 1, 1, 1, 'test.jpg');
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with("INSERT INTO funkos (name, price, stock, category_id, image) VALUES (:name, :price, :stock, :category_id, :image)")
            ->willReturn($this->stmt);

        $this->stmt->expects($this->exactly(5))
            ->method('bindValue')
            ->willReturn(true);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->assertNull($this->funkoService->create($funko));
    }

    public function testUpdate()
    {
        $funko = new Funko(1, 'test', 1, 1, 1, 'test.jpg');
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with("UPDATE funkos SET name = :name, price = :price, stock = :stock, category_id = :category_id WHERE id = :id")
            ->willReturn($this->stmt);

        $this->stmt->expects($this->exactly(5))
            ->method('bindValue')
            ->willReturn(true);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->assertNull($this->funkoService->update($funko));
    }

    public function testExistsById()
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM funkos WHERE id = :id')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('bindValue')
            ->with('id', 1)
            ->willReturn(true);

        $this->stmt->expects($this->once())
            ->method('execute');

        $this->stmt->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $this->assertTrue($this->funkoService->existsById(1));
    }

    public function testGetFunkoById()
    {
        $expected = new Funko();
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT f.*, c.name as category_name FROM funkos f JOIN categories c ON f.category_id = c.id WHERE f.id = :id')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('bindValue')
            ->with('id', 1)
            ->willReturn(true);

        $this->stmt->expects($this->once())
            ->method('execute');

        $this->stmt->expects($this->once())
            ->method('setFetchMode')
            ->with(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Funko::class)
            ->willReturn(true);

        $this->stmt->expects($this->once())
            ->method('fetch')
            ->willReturn($expected);

        $this->assertEquals($expected, $this->funkoService->getFunkoById(1));
    }
}
