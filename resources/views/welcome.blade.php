<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Notes Application</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'inter': ['Inter', 'sans-serif'],
                    },
                    colors: {
                        border: "hsl(214.3 31.8% 91.4%)",
                        input: "hsl(214.3 31.8% 91.4%)",
                        ring: "hsl(222.2 84% 4.9%)",
                        background: "hsl(0 0% 100%)",
                        foreground: "hsl(222.2 84% 4.9%)",
                        primary: {
                            DEFAULT: "hsl(222.2 47.4% 11.2%)",
                            foreground: "hsl(210 40% 98%)",
                        },
                        secondary: {
                            DEFAULT: "hsl(210 40% 96%)",
                            foreground: "hsl(222.2 84% 4.9%)",
                        },
                        muted: {
                            DEFAULT: "hsl(210 40% 96%)",
                            foreground: "hsl(215.4 16.3% 46.9%)",
                        },
                        accent: {
                            DEFAULT: "hsl(210 40% 96%)",
                            foreground: "hsl(222.2 84% 4.9%)",
                        },
                    },
                }
            }
        }
    </script>
</head>
<body class="font-inter bg-background text-foreground antialiased">
    <!-- Header -->
    <header class="sticky top-0 z-50 w-full border-b border-border/40 bg-background/95 backdrop-blur supports-[backdrop-filter]:bg-background/60">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-primary shadow-sm">
                        <svg class="h-5 w-5 text-primary-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <span class="text-xl font-semibold tracking-tight">Notes</span>
                </div>

                <!-- Navigation -->
                <nav class="hidden md:flex items-center space-x-1">
                    <a href="/login" class="inline-flex items-center justify-center rounded-lg px-4 py-2 text-sm font-medium text-muted-foreground transition-colors hover:bg-accent hover:text-accent-foreground">
                        Sign in
                    </a>
                    <a href="/register" class="inline-flex items-center justify-center rounded-lg px-4 py-2 text-sm font-medium bg-primary text-primary-foreground shadow-sm transition-colors hover:bg-primary/90">
                        Sign up
                    </a>
                </nav>

                <!-- Mobile Navigation -->
                <div class="flex md:hidden items-center space-x-2">
                    <a href="/login" class="inline-flex items-center justify-center rounded-lg px-3 py-2 text-sm font-medium text-muted-foreground transition-colors hover:bg-accent hover:text-accent-foreground">
                        Sign in
                    </a>
                    <a href="/register" class="inline-flex items-center justify-center rounded-lg px-3 py-2 text-sm font-medium bg-primary text-primary-foreground shadow-sm transition-colors hover:bg-primary/90">
                        Sign up
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1">
        <!-- Hero Section -->
        <section class="relative py-20 sm:py-32 lg:py-40">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mx-auto max-w-4xl text-center">
                    <div class="space-y-6">
                        <h1 class="text-4xl font-bold tracking-tight sm:text-5xl lg:text-6xl">
                            <span class="block">Notes Application</span>
                        </h1>
                        <p class="mx-auto max-w-2xl text-lg leading-8 text-muted-foreground sm:text-xl">
                            A simple and efficient way to capture your thoughts, organize your ideas, and keep track of what matters most.
                        </p>
                    </div>

                    <!-- CTA Buttons -->
                    <div class="mt-10 flex flex-col items-center gap-4 sm:flex-row sm:justify-center">
                        <a href="/register" class="inline-flex w-full sm:w-auto items-center justify-center rounded-xl bg-primary px-8 py-3 text-sm font-medium text-primary-foreground shadow-lg transition-colors hover:bg-primary/90 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary">
                            Get started
                        </a>
                        <a href="/login" class="inline-flex w-full sm:w-auto items-center justify-center rounded-xl border border-input bg-background px-8 py-3 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ring">
                            Sign in
                        </a>
                    </div>

                    <!-- Compact Features -->
                    <div class="mt-16 grid grid-cols-1 gap-6 sm:grid-cols-3 sm:gap-4">
                        <div class="flex flex-col items-center space-y-3 text-center">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-secondary">
                                <svg class="h-6 w-6 text-secondary-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-medium text-foreground">Create & Edit</h3>
                                <p class="mt-1 text-sm text-muted-foreground">Clean, distraction-free interface</p>
                            </div>
                        </div>

                        <div class="flex flex-col items-center space-y-3 text-center">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-secondary">
                                <svg class="h-6 w-6 text-secondary-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-medium text-foreground">Organize</h3>
                                <p class="mt-1 text-sm text-muted-foreground">Keep notes easily accessible</p>
                            </div>
                        </div>

                        <div class="flex flex-col items-center space-y-3 text-center">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-secondary">
                                <svg class="h-6 w-6 text-secondary-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-medium text-foreground">Secure</h3>
                                <p class="mt-1 text-sm text-muted-foreground">Private and secure notes</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="border-t border-border/40 bg-muted/30">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-center">
                <p class="text-sm text-muted-foreground">
                    Built with Laravel and Tailwind CSS
                </p>
            </div>
        </div>
    </footer>
</body>
</html>