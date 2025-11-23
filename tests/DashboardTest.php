<?php

use PHPUnit\Framework\TestCase;

require_once 'dashboard.php'; // Include the file containing the function

class DashboardTest extends TestCase
{
    public function testGenerateUserInitials(): void
    {
        // Test case 1: Basic name
        $this->assertEquals('JD', generateUserInitials('John Doe'));

        // Test case 2: Name with middle name
        $this->assertEquals('JAD', generateUserInitials('John Adam Doe'));

        // Test case 3: Single name
        $this->assertEquals('J', generateUserInitials('John'));

        // Test case 4: Name with extra spaces
        $this->assertEquals('JD', generateUserInitials('John  Doe'));
    }
}
