# BookQuest

Welcome to BookQuest, the collaborative repository developed as part of the IT490 course at the New Jersey Institute of Technology. This repository serves as the central hub for the BookQuest project, where students can access project resources, contribute code, and participate in the development process.

For additional details, refer to our [proposal.md](./docs/proposal.md).

## Want to Contribute?

Excited to contribute to BookQuest? Check out our [Development Environment guide](/docs/vm-environment.md) to get started on making BookQuest even better!

## More Information

Explore the inner workings of BookQuest through our detailed README files:

| Folder           | Description                                                                     | Link                              |
| ---------------- | ------------------------------------------------------------------------------- | --------------------------------- |
| **Scripts**      | Folder for miscellaneous scripts, e.g., startup, service_manager.               | [README](./scripts/README.md) |
| **VMs/backend**  | Website's Core, hosting the database, message broker, and most "services".      | [README](./VMs/backend/README.md) |
| **VMs/dmz**      | Middle ground between API and backend.                                          | [README](./VMs/dmz/README.md) |
| **VMs/frontend** | Contains files to host website frontend, powered by Apache.                     | [README](./VMs/frontend/README.md) |
| **VMs/deployer** | Manages VM "packages" and installation logic, simplifying project distribution. | [README](./VMs/deployer/README.md) |