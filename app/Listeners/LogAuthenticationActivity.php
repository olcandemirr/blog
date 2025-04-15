<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Registered; // Yeni kullanıcı kaydı için
use Illuminate\Http\Request;

class LogAuthenticationActivity
{
    protected $request;

    /**
     * Create the event listener.
     *
     * @param Request $request
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Handle user login events.
     */
    public function handleLogin(Login $event)
    {
        log_activity(
            'User logged in',
            $event->user,
            ['ip_address' => $this->request->ip(), 'user_agent' => $this->request->userAgent()],
            'auth'
        );
    }

    /**
     * Handle user logout events.
     */
    public function handleLogout(Logout $event)
    {
        if ($event->user) { // Ensure user exists before logging logout
             log_activity(
                'User logged out',
                $event->user,
                ['ip_address' => $this->request->ip(), 'user_agent' => $this->request->userAgent()],
                'auth'
            );
        }
    }
    
    /**
     * Handle user registration events.
     */
    public function handleRegistered(Registered $event)
    {
        log_activity(
            'User registered',
            $event->user,
            ['ip_address' => $this->request->ip(), 'user_agent' => $this->request->userAgent()],
            'auth'
        );
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     * @return void
     */
    public function subscribe($events)
    {
        $events->listen(
            Login::class,
            [LogAuthenticationActivity::class, 'handleLogin']
        );

        $events->listen(
            Logout::class,
            [LogAuthenticationActivity::class, 'handleLogout']
        );
        
        $events->listen(
            Registered::class, // Registered eventi ekleniyor
            [LogAuthenticationActivity::class, 'handleRegistered']
        );
    }
} 