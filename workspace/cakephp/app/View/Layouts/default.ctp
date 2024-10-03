<!DOCTYPE html>
<html lang="en">
<head>
	<?php echo $this->Html->charset(); ?>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>
		<?php echo $this->fetch('title'); ?>
	</title>
	<?php
		echo $this->Html->meta('icon');
		echo $this->Html->css('style');
		echo $this->Html->css('theme');

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>
</head>
<body class="nk-body bg-white npc-default">
	<div id="app" class="nk-app-root">
		<div class="nk-main">
			<div class="nk-wrap">
				<header class="nk-header nk-header-fixed is-light">
					<div class="container-lg wide-xl">
						<div class="nk-header-wrap">
							<div class="nk-header-brand">
								<?php
									echo $this->Html->link(
										$this->Html->image('cake.icon.png', array('alt' => 'Brand Name', 'class' => 'logo-dark logo-img')),
										array('controller' => 'pages', 'action' => 'display', 'home'),
										array('class' => 'logo-link', 'escape' => false)
									);
								?>
							</div>
							<?php if (AuthComponent::user('id')): ?>
							<div class="nk-header-menu">
								<ul class="nk-menu nk-menu-main">
									<li class="nk-menu-item">
										<?php
										echo $this->Html->link(
											$this->Html->tag('span', __('Messages'), array('class' => 'nk-menu-text')),
											array('controller' => 'messages', 'action' => 'index'),
											array('escape' => false, 'class' => 'nk-menu-link')
										);
										?>
									</li>
								</ul>
							</div>
							<?php endif; ?>
							<div class="nk-header-tools">
								<ul class="nk-quick-nav">
									<?php if (!AuthComponent::user('id')): ?>
									<li>
										<?php
											echo $this->Html->link(
												$this->Html->tag('span', __('Login'), array('class' => 'nk-menu-text')),
												array('controller' => 'users', 'action' => 'login'),
												array('escape' => false, 'class' => $this->App->isActive(array('controller' => 'users', 'action' => 'login')))
											);
										?>
									</li>
									<li>
										<?php
											echo $this->Html->link(
												$this->Html->tag('span', __('Register'), array('class' => 'nk-menu-text')),
												array('controller' => 'users', 'action' => 'register'),
												array('escape' => false, 'class' => $this->App->isActive(array('controller' => 'users', 'action' => 'register')))
											);
										?>
									</li>
									<?php else: ?>
									<li>
										<?php
											echo $this->Html->link(
												$this->Html->tag('span', __(AuthComponent::user('name')), array('class' => 'text-primary fw-bold')),
												array('controller' => 'users', 'action' => 'profile'),
												array('escape' => false)
											);
										?>
									</li>
									<li>
										<?php
											echo $this->Html->link(
												$this->Html->tag('em', null, array('class' => 'icon ni ni-power text-dark', 'escape' => true)),
												array('controller' => 'users', 'action' => 'logout'),
												array('escape' => false)
											);
										?>
									</li>
									<?php endif; ?>
								</ul>
							</div>
						</div>
					</div>
				</header>

				<div class="nk-content">
					<div class="container wide-xl">
						<div class="nk-content-inner">
							<div class="nk-content-body pt-0">
								<div class="nk-content-wrap">
									<?php echo $this->fetch('content'); ?>
								</div>
							</div>
						</div>
					</div>
				</div>

				<footer class="nk-footer">
					<div class="container wide-xl">
						<div class="nk-footer-wrap g-2">
							<div class="nk-footer-copyright">
								&copy; 2024 Message Board, Alright Reserved
							</div>
						</div>
					</div>
				</footer>
			</div>
		</div>
	</div>

	<?php
		echo $this->Html->script('bundle');
		echo $this->Html->script('script');
		echo $this->fetch('custom_script');
	?>
</body>
</html>
