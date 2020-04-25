<?php

namespace App\GraphQL\Mutations;

use App\Services\AuthService;
use Auth;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Exceptions\AuthenticationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Validator;

class AuthResolver
{

    private AuthService $auth;

    public function __construct(AuthService $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Return a value for the field.
     *
     * @param  null  $rootValue Usually contains the result returned from the parent field. In this case, it is always `null`.
     * @param  mixed[]  $args The arguments that were passed into the field.
     * @param  \Nuwave\Lighthouse\Support\Contracts\GraphQLContext  $context Arbitrary data that is shared between all fields of a single query.
     * @param  \GraphQL\Type\Definition\ResolveInfo  $resolveInfo Information about the query itself, such as the execution state, the field name, path to the field from the root, and more.
     * @return mixed
     */
    public function login($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        if (!Auth::attempt([
            'email' => $args['email'],
            'password' => $args['password']
        ])) {
            throw new AuthenticationException('Email or password was incorrect.');
        }

        return true;
    }

    public function register($rootValue, array $args)
    {
        $user = $args['user'];
        return $this->auth->registerPassword($user['email'], $user['password'], $user['firstName'], $user['lastName']);
    }

    public function logout()
    {
        Auth::logout();
        return true;
    }
}
