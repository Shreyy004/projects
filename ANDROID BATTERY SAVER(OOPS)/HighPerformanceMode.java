import java.util.ArrayList;
import java.util.List;
import java.util.Scanner;

class HighPerformanceMode extends Battery implements BrightnessControl, Permissions , BatteryModeCustomization {
    private List<String> grantedPermissions;

    public HighPerformanceMode(int level) {
        super(level);
        grantedPermissions = new ArrayList<>();
    }

    @Override
    public void setBrightness(int level) {
        System.out.println("Setting high brightness level: " + level);
    }

    @Override
    public void grantPermission(String permission) {
        grantedPermissions.add(permission);
        System.out.println("Permission granted in High Performance Mode: " + permission);
    }

    public List<String> getGrantedPermissions() {
        return grantedPermissions;
    }

     @Override
    public void customizeBatteryMode(Scanner scanner) {
        int choice;
        do {
            System.out.println("Menu for High Performance Mode Customization:");
            System.out.println("1. Adjust Brightness");
            System.out.println("2. Grant Permission");
            System.out.println("3. Exit");

            System.out.print("Enter your choice: ");
            choice = scanner.nextInt();

            switch (choice) {
                case 1:
                    System.out.print("Enter brightness level: ");
                    int brightnessLevel = scanner.nextInt();
                    setBrightness(brightnessLevel);
                    break;
                case 2:
                    System.out.print("Enter permission to grant: ");
                    String permission = scanner.next();
                    grantPermission(permission);
                    break;
                case 3:
                    break;
                default:
                    System.out.println("Invalid choice. Please enter a valid option.");
            }

        } while (choice != 3);
    }
}

