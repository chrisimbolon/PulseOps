protected $middlewareGroups = [
    'web' => [
        // Existing middleware...
        \App\Http\Middleware\ResolveTenant::class,
    ],
];
