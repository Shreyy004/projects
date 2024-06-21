// Base Class
import java.util.Scanner;

class Battery {
    private int level;

    public Battery(int level) {
        setLevel(level);
    }

    public int getLevel() {
        return level;
    }

    public void setLevel(int level) {
        if (level >= 0 && level <= 100) {
            this.level = level;
        } else {
            System.out.println("Invalid battery percentage. It should be between 0 and 100.");
        }
    }
}

interface PowerSaver {
    void enablePowerSavingMode();
}

interface BrightnessControl {
    void setBrightness(int level);
}

interface AppUsageStatistics {
    void trackAppUsage(String appName);
}

interface Permissions {
    void grantPermission(String permission);
}

interface BatteryModeCustomization {
    void customizeBatteryMode(Scanner scanner);
}

