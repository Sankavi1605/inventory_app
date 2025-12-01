<?php

namespace Tests\Helpers;

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../app/helpers/session_helper.php';

class SessionHelperTest extends TestCase
{
    protected function setUp(): void
    {
        $_SESSION = [];
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        $_SERVER['HTTP_USER_AGENT'] = 'PHPUnit';
    }

    public function testRoleHierarchyContainsAllExpectedRoles(): void
    {
        $map = getRoleHierarchyMap();
        $expected = ['guest', 'external', 'resident', 'maintenance', 'security', 'admin', 'superadmin'];

        foreach ($expected as $role) {
            $this->assertArrayHasKey($role, $map, sprintf('Role "%s" missing from hierarchy map', $role));
        }

        $this->assertLessThan($map['superadmin'], $map['superadmin'] + 1); // simple sanity check
    }

    public function testHasRoleRequiresLogin(): void
    {
        $this->assertFalse(hasRole('admin'));
    }

    public function testHasRoleHonorsHierarchy(): void
    {
        $_SESSION['user_id'] = 1;
        $_SESSION['logged_in'] = true;
        $_SESSION['user_role'] = 'superadmin';

        $this->assertTrue(hasRole('maintenance'));
        $this->assertTrue(hasRole('admin'));
    }

    public function testHasRoleFailsWhenPrivilegeTooLow(): void
    {
        $_SESSION['user_id'] = 2;
        $_SESSION['logged_in'] = true;
        $_SESSION['user_role'] = 'resident';

        $this->assertFalse(hasRole('admin'));
        $this->assertTrue(hasRole('external'));
    }
}
