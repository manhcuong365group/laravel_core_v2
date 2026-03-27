# Changelog

## [2026-03-27]
### Changed
- Tối ưu N+1 Query trong list bài viết (Article/IndexPage) bằng Livewire `#[Computed]`.
- Cấu hình tự động dọn dẹp Activity Log cũ bằng cron schedule `activitylog:clean`.
- Dịch logs tiếng việt chuyên nghiệp ở Article Model.

### Fixed
- Vá lổ hổng nhập nội dung XSS trong `ArticleData.php` bằng cách lọc thủ công thẻ `<script>` dư thừa.
