<?php

namespace services;

require_once __DIR__ . '/../../public/services/UserService.php';
require_once __DIR__ . '/../../public/models/User.php';

use Exception;
use models\User;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;

class UserServiceTest extends TestCase
{
    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->stmt = $this->createMock(PDOStatement::class);
        $this->userService = new UserService($this->pdo);
    }


    public function testAuthenticateWithInvalidUser()
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM users WHERE username = :username')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':username', 'test');

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->stmt->expects($this->once())
            ->method('fetch')
            ->with(PDO::FETCH_ASSOC)
            ->willReturn(false);

        try {
            $this->userService->authenticate('test', '1234');
        } catch (Exception $e) {
            $this->assertEquals('Usuario o contraseña no válidos', $e->getMessage());
        }
    }

    public function testAuthenticate()
    {
        $hashedPassword = password_hash('1234', PASSWORD_DEFAULT);
        $user = new User(1, 'test', $hashedPassword, ['admin']);
        $rolesStmt = $this->createMock(PDOStatement::class);

        $this->pdo->expects($this->exactly(2))
            ->method('prepare')
            ->willReturnOnConsecutiveCalls($this->stmt, $rolesStmt);

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':username', 'test');

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->stmt->expects($this->once())
            ->method('fetch')
            ->with(PDO::FETCH_ASSOC)
            ->willReturn([
                'id' => 1,
                'username' => 'test',
                'password' => $hashedPassword
            ]);


        $rolesStmt->expects($this->once())
            ->method('bindParam')
            ->with(':user_id', 1);

        $rolesStmt->expects($this->once())
            ->method('execute');

        $rolesStmt->expects($this->once())
            ->method('fetchAll')
            ->with(PDO::FETCH_COLUMN)
            ->willReturn(['admin']);

        $this->assertEquals($user, $this->userService->authenticate('test', '1234'));
    }
}
