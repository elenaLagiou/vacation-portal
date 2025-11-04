<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Elagiou\VacationPortal\Services\AuthService;
use Elagiou\VacationPortal\Repositories\AuthRepository;
use Elagiou\VacationPortal\Models\User;
use Elagiou\VacationPortal\DTO\LoginDTO;
use PDO;

class AuthTest extends TestCase
{
    private PDO $pdo;
    private AuthService $authService;
    private AuthRepository $authRepo;
    private string $testUsername = 'testuser';
    private string $testPassword = 'password123';

    protected function setUp(): void
    {
        parent::setUp();

        // Create an in-memory SQLite database for testing
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Create users table with soft deletes
        $this->pdo->exec("
            CREATE TABLE users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                role_id INTEGER NOT NULL,
                username VARCHAR(50) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL,
                first_name VARCHAR(50) NOT NULL,
                last_name VARCHAR(50) NOT NULL,
                deleted_at TIMESTAMP NULL DEFAULT NULL
            )
        ");

        // Create test dependencies
        $this->authRepo = new AuthRepository($this->pdo);
        $this->authService = new AuthService($this->authRepo);

        // Create a test user
        $hashedPassword = password_hash($this->testPassword, PASSWORD_DEFAULT);
        $this->pdo->exec("
            INSERT INTO users (role_id, username, password, email, first_name, last_name)
            VALUES (
                3, 
                '$this->testUsername', 
                '$hashedPassword',
                'test@example.com',
                'Test',
                'User'
            )
        ");
    }

    public function testSuccessfulLogin(): void
    {
        $loginDto = new LoginDTO([
            'username' => $this->testUsername,
            'password' => $this->testPassword
        ]);

        $result = $this->authService->login($loginDto);

        $this->assertNotNull($result);
        $this->assertEquals($this->testUsername, $result['username']);
        $this->assertEquals(3, $result['role_id']); // Employee role
    }

    public function testFailedLoginWithWrongPassword(): void
    {
        $loginDto = new LoginDTO([
            'username' => $this->testUsername,
            'password' => 'wrongpassword'
        ]);

        $result = $this->authService->login($loginDto);

        $this->assertNull($result);
    }

    public function testFailedLoginWithNonexistentUser(): void
    {
        $loginDto = new LoginDTO([
            'username' => 'nonexistent',
            'password' => $this->testPassword
        ]);

        $result = $this->authService->login($loginDto);

        $this->assertNull($result);
    }

    public function testLoginWithSoftDeletedUser(): void
    {
        // Soft delete our test user
        $this->pdo->exec("
            UPDATE users 
            SET deleted_at = CURRENT_TIMESTAMP 
            WHERE username = '$this->testUsername'
        ");

        $loginDto = new LoginDTO([
            'username' => $this->testUsername,
            'password' => $this->testPassword
        ]);

        $result = $this->authService->login($loginDto);

        $this->assertNull($result);
    }

    public function testLogout(): void
    {
        // First login
        $loginDto = new LoginDTO([
            'username' => $this->testUsername,
            'password' => $this->testPassword
        ]);
        $this->authService->login($loginDto);

        // Then logout
        $this->authService->logout();

        // Verify user is logged out
        $this->assertFalse($this->authService->check());
        $this->assertNull($this->authService->currentUser());
    }

    protected function tearDown(): void
    {
        // Clean up
        $this->pdo->exec("DROP TABLE users");
        parent::tearDown();
    }
}
