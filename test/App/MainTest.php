<?php

namespace App;

use PHPUnit\Framework\TestCase;

class MainTest extends TestCase
{
    public function testSameLength()
    {
        $routes = [
            'api/projects/{project}',
            'api/projects/test',
        ];

        $main = new Main($routes);
        $result = $main->execute();

        self::assertSame(
            [
                'api/projects/test',
                'api/projects/{project}',
            ],
            $result
        );
    }

    public function testOneSideIsLonger()
    {
        $routes = [
            'api/projects/{project}/versions/{version}',
            'api/projects/{project}/versions/{version}/default_preset',
        ];

        $main = new Main($routes);
        $result = $main->execute();

        self::assertSame(
            [
                'api/projects/{project}/versions/{version}',
                'api/projects/{project}/versions/{version}/default_preset',
            ],
            $result
        );
    }

    public function testLexicalOrder()
    {
        $routes = [
            'aaa',
            'bbb',
        ];

        $main = new Main($routes);
        $result = $main->execute();

        self::assertSame(
            [
                'aaa',
                'bbb',
            ],
            $result
        );
    }

    public function testBySato()
    {
        $routes = [
            'api/projects/{project}/versions/{version}',
            'api/projects/{project}/versions/{version}/default_preset',
            'api/projects/{project}/members/{user}',
            'api/projects/{project}/drafts/{draft}/announcement:copy',
            'api/projects/{project}/drafts/groups/{group}/subgroups',
        ];

        $main = new Main($routes);
        $result = $main->execute();

        self::assertSame(
            [
                'api/projects/{project}/drafts/groups/{group}/subgroups',
                'api/projects/{project}/drafts/{draft}/announcement:copy',
                'api/projects/{project}/members/{user}',
                'api/projects/{project}/versions/{version}',
                'api/projects/{project}/versions/{version}/default_preset',
            ],
            $result
        );
    }
}
