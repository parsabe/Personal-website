class ServerConfig {
  // Base Domain & Subdomains Configuration
  static const String primaryBaseUrl = 'https://parsabe.com';
  static const String sandikaSubdomain = 'https://sandika.parsabe.com';
  static const String nigmaSubdomain = 'https://nigma.parsabe.com';
  static const String blackwallSubdomain = 'https://blackwall.parsabe.com';
  static const String vectraSubdomain = 'https://vectra.parsabe.com';
  static const String webmailSubdomain = 'https://parsa.parsabe.com';
  static const String bluePearlSubdomain = 'https://blue-pearl.parsabe.com';
  static const String vpnSubdomain = 'https://vpn.parsabe.com';
  static const String adminPanelSubdomain = 'https://panel.parsabe.com';

  static const String localEngineUrl = 'http://127.0.0.1:8001';

  // 2. Authentication & 2FA Endpoints
  static const String loginEndpoint = '$primaryBaseUrl/login';
  static const String registerEndpoint = '$primaryBaseUrl/register';
  static const String twoFactorVerifyEndpoint = '$primaryBaseUrl/2fa';
  static String switchLanguage(String locale) => '$primaryBaseUrl/lang/$locale';

  // 3. Sandika Portal Endpoints
  static const String sandikaMain = '$primaryBaseUrl/sandika';
  static const String sandikaVoiceLog = '$primaryBaseUrl/sandika/voice-log';
  static const String sandikaFileUpload = '$primaryBaseUrl/sandika/file-upload';
  static const String sandikaStory = '$primaryBaseUrl/sandika/story';
  static const String sandikaDictionary = '$primaryBaseUrl/sandika/dictionary';
  static const String sandikaGit = '$primaryBaseUrl/sandika/git';
  static String sandikaGitUpdate(String id) => '$primaryBaseUrl/sandika/git/$id/update';
  static String sandikaGitDelete(String id) => '$primaryBaseUrl/sandika/git/$id/delete';
  static const String sandikaArkham = '$primaryBaseUrl/sandika/arkham';

  // 4. Nigma Riddles Portal Endpoints
  static const String nigmaMain = '$primaryBaseUrl/nigma';
  static const String nigmaSolve = '$primaryBaseUrl/nigma/solve';

  // 5. Online Chat Portal & Real-Time Endpoints
  static const String chatMain = '$primaryBaseUrl/chat';
  static const String chatMessages = '$primaryBaseUrl/chat/messages';
  static const String chatUsers = '$primaryBaseUrl/chat/users';
  static const String chatSend = '$primaryBaseUrl/chat/send';
  static const String chatUpload = '$primaryBaseUrl/chat/upload';
  static const String chatReact = '$primaryBaseUrl/chat/react';
  static const String chatProfile = '$primaryBaseUrl/chat/profile';
  static const String chatStories = '$primaryBaseUrl/chat/stories';
  static const String chatStoriesCreate = '$primaryBaseUrl/chat/stories';
  static const String chatStoriesVote = '$primaryBaseUrl/chat/stories/vote';

  // 6. User Profile & Social Feed Endpoints
  static const String userProfile = '$primaryBaseUrl/user/profile';
  static const String userProfileUpdate = '$userProfile/update';
  static const String userSelectAvatar = '$primaryBaseUrl/user/profile/select-avatar';
  static const String userSelectHeader = '$primaryBaseUrl/user/profile/select-header';
  static const String userDeleteAvatar = '$primaryBaseUrl/user/profile/delete-avatar';
  static const String userDeleteHeader = '$primaryBaseUrl/user/profile/delete-header';
  static String userFollowToggle(String id) => '$primaryBaseUrl/user/$id/follow';
  static String userStats(String id) => '$primaryBaseUrl/user/$id/stats';
  static const String userStoriesArchive = '$primaryBaseUrl/user/stories/archive';
  static String userStoryHighlightToggle(String id) => '$primaryBaseUrl/user/stories/$id/highlight';
  static const String userStoryArchivesCreate = '$primaryBaseUrl/user/story-archives/create';
  static String userStoryArchiveDelete(String id) => '$primaryBaseUrl/user/story-archives/$id/delete';
  static const String userPostsCreate = '$primaryBaseUrl/user/posts/create';
  static String userPostsFetch(String id) => '$primaryBaseUrl/user/$id/posts';
  static const String userPublicFeed = '$primaryBaseUrl/user/posts/feed';
  static String userPostLike(String id) => '$primaryBaseUrl/user/posts/$id/like';
  static String userPostRepost(String id) => '$primaryBaseUrl/user/posts/$id/repost';
  static String userPostBookmark(String id) => '$primaryBaseUrl/user/posts/$id/bookmark';
  static String userPostComment(String id) => '$primaryBaseUrl/user/posts/$id/comment';
  static String userPostDelete(String id) => '$primaryBaseUrl/user/posts/$id/delete';

  // 7. Blog & Publications Endpoints
  static const String blogList = '$primaryBaseUrl/blog';
  static const String blogStore = '$primaryBaseUrl/blog';
  static String articleShow(String slug) => '$primaryBaseUrl/publications/articles/$slug';
  static const String userArticles = '$primaryBaseUrl/user/articles';
  static String userArticleUpdate(String id) => '$primaryBaseUrl/user/articles/$id/update';
  static String userArticleDelete(String id) => '$primaryBaseUrl/user/articles/$id/delete';

  // 8. Projects & AI Services Endpoints
  static const String projectsOverview = '$primaryBaseUrl/projects';
  static const String vectraService = '$primaryBaseUrl/projects/vectra';
  static const String blackwallInterface = '$primaryBaseUrl/projects/blackwall';
  static const String blackwallChat = '$primaryBaseUrl/projects/blackwall/chat';

  // 9. Feedback Endpoints
  static const String csFeedbackMain = '$primaryBaseUrl/cs-portal/feedback';
  static const String csSubmitFeedback = '$primaryBaseUrl/cs-portal/feedback';
  static const String csVerifyEmail = '$primaryBaseUrl/cs-portal/feedback/verify';
  static const String csResetFeedbackSession = '$primaryBaseUrl/cs-portal/feedback/reset';

  // 10. General & Contact Endpoints
  static const String searchEndpoint = '$primaryBaseUrl/search';
  static const String contactEndpoint = '$primaryBaseUrl/contact';
}
