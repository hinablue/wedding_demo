CREATE TABLE `messages` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fb_app_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `file` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('published','deleted') COLLATE utf8mb4_unicode_ci DEFAULT 'published',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Messages';

ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fb_app_id` (`fb_app_id`),
  ADD KEY `status` (`status`);