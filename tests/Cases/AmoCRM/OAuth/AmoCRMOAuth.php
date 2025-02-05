<?php

declare(strict_types=1);

namespace Cases\AmoCRM\OAuth;

use Lcobucci\JWT\Validation\Constraint\ValidAt;
use Lcobucci\JWT\Validation\Constraint\LooseValidAt;
use Lcobucci\JWT\Validation\Constraint;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class AmoCRMOAuth extends TestCase
{
    public function testCreateValidAtConstraintReturnsConstraintInstance()
    {
        // Создаем заглушку для тестируемого класса
        $oAuth = $this->getMockBuilder(\AmoCRM\OAuth\AmoCRMOAuth::class)
            ->disableOriginalConstructor()
            ->getMock();

        // Подготавливаем Reflection для доступа к приватному методу
        $reflection = new ReflectionClass($oAuth);
        $method = $reflection->getMethod('createValidAtConstraint');
        $method->setAccessible(true);

        // Проверяем, что метод возвращает объект типа Constraint
        $result = $method->invoke($oAuth);

        $this->assertInstanceOf(Constraint::class, $result);
    }

    public function testCreateValidAtConstraintThrowsExceptionWhenNoConstraints()
    {
        // Создаем заглушку для тестируемого класса
        $oAuth = $this->getMockBuilder(\AmoCRM\OAuth\AmoCRMOAuth::class)
            ->disableOriginalConstructor()
            ->getMock();

        // Подготавливаем Reflection для доступа к приватному методу
        $reflection = new ReflectionClass($oAuth);
        $method = $reflection->getMethod('createValidAtConstraint');
        $method->setAccessible(true);

        if (!class_exists(ValidAt::class) && !class_exists(LooseValidAt::class)) {
            $this->expectException(\RuntimeException::class);
            $this->expectExceptionMessage('Neither LooseValidAt nor ValidAt are available.');
        } else {
            $this->expectNotToPerformAssertions();
        }

        // Вызов приватного метода
        $method->invoke($oAuth);
    }
}
