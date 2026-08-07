import 'dart:ui';

class UiBlur {
  static ImageFilter blur(double sigmaX, double sigmaY) {
    return ImageFilter.blur(sigmaX: sigmaX, sigmaY: sigmaY);
  }
}
