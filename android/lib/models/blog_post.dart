class BlogPost {
  final String id;
  final String title;
  final String author;
  final String date;
  final String readTime;
  final String excerpt;
  final String content;

  BlogPost({
    required this.id,
    required this.title,
    required this.author,
    required this.date,
    required this.readTime,
    required this.excerpt,
    required this.content,
  });

  // Empty sample posts list so zero dummy journals exist
  static List<BlogPost> get samplePosts => [];
}
