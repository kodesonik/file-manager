# Laravel Bucket Project Requirements

## Project Overview
- Framework: Laravel
- Database: SQLite
- Admin Panel: Voyager

## Administration Features
- Implement BREAD (Browse, Read, Edit, Add, Delete) for Projects in Voyager admin panel
- Implement BREAD for generating API keys for projects in Voyager admin panel
- Implement User assignments for projects(multiple users can be assigned to a project) in Voyager admin panel
- Automatic folder creation in storage when a new project is created
- Restrict media section to assigned project folders for users with role "user"

## Project Management
### Project Attributes
- Name
- Description
- Unique API Key for file uploads
- User assignments

## User Role Management
- User role with project-level access
- Media section restricted to assigned project folders

## API Endpoints
### File Upload Endpoint
- Method: POST
- Authentication: Project API Key
- Feature: Multiple file upload
- Path Structure: `storage/{project_name}/{sub_path}`
- Example: `storage/booking/avatars`

### File Delete Endpoint
- Method: DELETE
- Authentication: Project API Key
- Requirement: Full file path for deletion

## Security Considerations
- API Key based authentication
- Role-based access control
- Secure file upload and delete mechanisms

## Technical Requirements
- Implement API middleware for key validation
- Create custom file upload handler
- Develop role-based view restrictions