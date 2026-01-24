<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?php echo $__env->yieldContent('title', 'Shop Home'); ?></title>
  
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  
  
  <?php echo $__env->yieldContent('styles'); ?>
</head>

<body class="bg-light">

  <!-- Header / Navbar -->
  <?php echo $__env->make('partials.client.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

  <!-- Main Content -->
  <main class="container py-4">
    <?php echo $__env->yieldContent('content'); ?>
  </main>

  <!-- Footer -->
  <?php echo $__env->make('partials.client.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

  <!-- Bootstrap 5 JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  
  
  <?php echo $__env->yieldContent('scripts'); ?>
</body>
</html><?php /**PATH C:\xampp\htdocs\php2lq\app\views/layouts/client.blade.php ENDPATH**/ ?>