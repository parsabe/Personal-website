class ChatMessage {
  final String id;
  final String sender;
  final String avatarUrl;
  final String message;
  final String timestamp;
  final bool isUser;
  final String? attachmentType; // 'photo', 'video', 'voice', 'document'
  final String? attachmentUrl;
  final String? fileName;
  final String? fileSize;
  final String? caption;
  final String? duration; // e.g. "0:15"
  int likes;
  int reposts;
  List<String> replies;

  ChatMessage({
    required this.id,
    required this.sender,
    required this.avatarUrl,
    required this.message,
    required this.timestamp,
    required this.isUser,
    this.attachmentType,
    this.attachmentUrl,
    this.fileName,
    this.fileSize,
    this.caption,
    this.duration,
    this.likes = 0,
    this.reposts = 0,
    List<String>? replies,
  }) : replies = replies ?? [];

  Map<String, dynamic> toJson() => {
        'id': id,
        'sender': sender,
        'avatarUrl': avatarUrl,
        'message': message,
        'timestamp': timestamp,
        'isUser': isUser,
        'attachmentType': attachmentType,
        'attachmentUrl': attachmentUrl,
        'fileName': fileName,
        'fileSize': fileSize,
        'caption': caption,
        'duration': duration,
        'likes': likes,
        'reposts': reposts,
        'replies': replies,
      };

  factory ChatMessage.fromJson(Map<String, dynamic> json) {
    return ChatMessage(
      id: json['id'] ?? DateTime.now().toString(),
      sender: json['sender'] ?? 'User',
      avatarUrl: json['avatarUrl'] ?? '',
      message: json['message'] ?? '',
      timestamp: json['timestamp'] ?? 'Just now',
      isUser: json['isUser'] ?? false,
      attachmentType: json['attachmentType'],
      attachmentUrl: json['attachmentUrl'],
      fileName: json['fileName'],
      fileSize: json['fileSize'],
      caption: json['caption'],
      duration: json['duration'],
      likes: json['likes'] ?? 0,
      reposts: json['reposts'] ?? 0,
      replies: (json['replies'] as List<dynamic>?)?.map((e) => e.toString()).toList(),
    );
  }

  static List<ChatMessage> get sampleMessages => [];
}

class UserStory {
  final String id;
  final String username;
  final String caption;
  final bool hasUnseen;

  UserStory({
    required this.id,
    required this.username,
    required this.caption,
    this.hasUnseen = true,
  });
}
