import 'package:flutter/material.dart';

class AppTheme {
  // Default Colors
  static const Color background = Color(0xFF000000);
  static const Color surface = Color(0xFF121212);
  static const Color glassCard = Color(0x331E293B);
  static const Color borderGlass = Color(0x22FFFFFF);

  static const Color telegramBlue = Color(0xFF2AABEE);
  static const Color orangePrimary = Color(0xFFF97316);
  static const Color pinkPrimary = Color(0xFFEC4899);
  static const Color cyanAccent = Color(0xFF06B6D4);
  static const Color emeraldAccent = Color(0xFF10B981);
  static const Color purpleAccent = Color(0xFF8B5CF6);

  static const LinearGradient primaryGradient = LinearGradient(
    colors: [orangePrimary, pinkPrimary],
    begin: Alignment.topLeft,
    end: Alignment.bottomRight,
  );

  static const LinearGradient telegramGradient = LinearGradient(
    colors: [telegramBlue, cyanAccent],
    begin: Alignment.topLeft,
    end: Alignment.bottomRight,
  );

  static const LinearGradient cyanPurpleGradient = LinearGradient(
    colors: [cyanAccent, purpleAccent],
    begin: Alignment.topLeft,
    end: Alignment.bottomRight,
  );

  static const Map<String, Color> themeBackgrounds = {
    'OLED Pitch Black': Color(0xFF000000),
    'Telegram Blue': Color(0xFF0F172A),
    'Cyberpunk Neon': Color(0xFF090D16),
    'Midnight Purple': Color(0xFF1E1B4B),
    'Emerald Matrix': Color(0xFF022C22),
    'Deep Space': Color(0xFF030712),
    'Sunset Orange': Color(0xFF451A03),
    'Solarized Dark': Color(0xFF002B36),
    'Rose Gold': Color(0xFF4C0519),
    'Ice Cyan': Color(0xFF083344),
    'Tokyo Night': Color(0xFF1A1B26),
    'Dracula Dark': Color(0xFF282A36),
    'Nord Frost': Color(0xFF2E3440),
    'Synthwave': Color(0xFF241442),
    'Obsidian Gold': Color(0xFF1C1917),
    'Emerald Forest': Color(0xFF064E3B),
    'Coral Red': Color(0xFF7F1D1D),
    'Sapphire Deep': Color(0xFF172554),
    'Amethyst Dark': Color(0xFF3B0764),
    'Slate Gray': Color(0xFF0F172A),
    'Vaporwave': Color(0xFF2E1065),
    'Aurora Borealis': Color(0xFF0284C7),
    'Titanium Black': Color(0xFF18181B),
    'Quantum Teal': Color(0xFF134E4A),
    'Royal Violet': Color(0xFF581C87),
  };

  static ThemeData getThemeByName(String themeName) {
    final bg = themeBackgrounds[themeName] ?? background;
    return darkTheme.copyWith(
      scaffoldBackgroundColor: bg,
      appBarTheme: darkTheme.appBarTheme.copyWith(backgroundColor: bg),
    );
  }

  static ThemeData get darkTheme {
    return ThemeData(
      useMaterial3: true,
      brightness: Brightness.dark,
      scaffoldBackgroundColor: background,
      primaryColor: telegramBlue,
      colorScheme: const ColorScheme.dark(
        primary: telegramBlue,
        secondary: orangePrimary,
        surface: surface,
        onSurface: Colors.white,
      ),
      cardTheme: CardThemeData(
        color: surface,
        elevation: 0,
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(20),
          side: const BorderSide(color: borderGlass),
        ),
      ),
      appBarTheme: const AppBarTheme(
        backgroundColor: background,
        elevation: 0,
        centerTitle: true,
        titleTextStyle: TextStyle(
          fontSize: 20,
          fontWeight: FontWeight.bold,
          color: Colors.white,
        ),
      ),
      fontFamily: 'Roboto',
    );
  }
}
